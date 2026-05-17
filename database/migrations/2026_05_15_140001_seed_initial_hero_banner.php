<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('hero_banners')->count() === 0) {
            DB::table('hero_banners')->insert([
                'imagen_desktop' => 'assets/HeroOnePlus15.png',
                'imagen_mobile'  => 'assets/HeroOneplus15Mobile.png',
                'enlace'         => null,
                'titulo'         => 'OnePlus 15',
                'activo'         => true,
                'orden'          => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('hero_banners')
            ->where('imagen_desktop', 'assets/HeroOnePlus15.png')
            ->delete();
    }
};
