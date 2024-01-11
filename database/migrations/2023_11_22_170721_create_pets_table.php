<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species');
            $table->string('race');
            $table->unsignedFloat('size');
            $table->unsignedInteger('age');
            $table->enum('sex', ['Macho', 'Hembra']);
            $table->enum('energy', ['Bajo', 'Medio', 'Alto']);
            $table->string('social_children');
            $table->string('social_dog');
            $table->string('social_cat');
            $table->text('description');
            $table->enum('status', ['Adoptado', 'Sin Adoptar']);
            $table->unsignedBigInteger('site_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
