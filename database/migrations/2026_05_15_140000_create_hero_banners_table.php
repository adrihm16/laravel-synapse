<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_banners', function (Blueprint $table) {
            $table->id('id_banner');
            $table->string('imagen_desktop', 255);
            $table->string('imagen_mobile', 255);
            $table->string('enlace', 255)->nullable();
            $table->string('titulo', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
            $table->index('activo', 'hero_banners_activo_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_banners');
    }
};
