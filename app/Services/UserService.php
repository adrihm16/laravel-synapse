<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Create a new user with hashed password.
     */
    public function createUser(array $data): User
    {
        $rol = $data['rol'] ?? 'cliente';
        $data['password'] = Hash::make($data['password']);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        $user->rol = $rol;
        $user->save();

        return $user;
    }

    /**
     * Update an existing user with hashed password if provided.
     */
    public function updateUser(User $user, array $data): User
    {
        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (array_key_exists('rol', $data)) {
            $user->rol = $data['rol'];
        }

        $user->save();

        return $user;
    }

    /**
     * Delete a user if it's not the current authenticated user.
     */
    public function deleteUser(User $user): bool
    {
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return false;
        }

        return $user->delete();
    }
}
