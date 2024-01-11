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
        Schema::table('vaccines', function (Blueprint $table) {
            // Agrega la columna 'image'. Puedes cambiar 'string' por 'text' si esperas nombres de archivo muy largos
            $table->string('image')->nullable()->after('vet'); // Asume que 'name' es una columna existente en la tabla
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccines', function (Blueprint $table) {
            // Elimina la columna si se revierte la migración
            $table->dropColumn('image');
        });
    }
};
