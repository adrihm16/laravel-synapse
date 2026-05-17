<?php

namespace Tests\Feature\Admin;

use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateHeroBannerRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $u = User::factory()->create();
        $u->rol = 'admin';
        $u->save();
        return $u;
    }

    private function client(): User
    {
        $u = User::factory()->create();
        $u->rol = 'cliente';
        $u->save();
        return $u;
    }

    /** @test */
    public function guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    /** @test */
    public function client_is_forbidden_from_admin_dashboard(): void
    {
        $this->actingAs($this->client())->get(route('admin.dashboard'))->assertForbidden();
    }

    /** @test */
    public function admin_can_access_dashboard(): void
    {
        $this->actingAs($this->admin())->get(route('admin.dashboard'))->assertStatus(200);
    }

    /** @test */
    public function client_is_forbidden_from_admin_resource_routes(): void
    {
        $client = $this->client();
        foreach (['admin.users.index', 'admin.products.index', 'admin.categories.index', 'admin.orders.index'] as $route) {
            $this->actingAs($client)->get(route($route))->assertForbidden();
        }
    }

    private function authorizeAs(string $class, ?User $user): bool
    {
        $req = new $class();
        $req->setUserResolver(fn () => $user);
        return $req->authorize();
    }

    /** @test */
    public function admin_form_requests_authorize_only_admins(): void
    {
        $admin = $this->admin();
        $client = $this->client();

        $requests = [
            StoreProductRequest::class,
            UpdateProductRequest::class,
            StoreUserRequest::class,
            StoreCategoryRequest::class,
            UpdateHeroBannerRequest::class,
            UpdateOrderStatusRequest::class,
        ];

        foreach ($requests as $class) {
            $this->assertTrue($this->authorizeAs($class, $admin), "{$class} should authorize admin");
            $this->assertFalse($this->authorizeAs($class, $client), "{$class} should reject non-admin");
            $this->assertFalse($this->authorizeAs($class, null), "{$class} should reject guest");
        }
    }

    /** @test */
    public function rol_field_is_not_mass_assignable(): void
    {
        $user = User::create([
            'name'     => 'Mallory',
            'email'    => 'mallory@evil.test',
            'password' => bcrypt('secret123'),
            'rol'      => 'admin',
        ]);

        // The 'rol' attribute must NOT have been set via mass assignment.
        $this->assertNotEquals('admin', $user->rol);
    }
}
