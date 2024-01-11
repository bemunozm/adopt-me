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
        Schema::create('organization_pet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('pet_id');
            $table->string('title')->nullable();
            $table->timestamp('meeting_date')->nullable(); // Fecha y hora de la reunión
            $table->enum('status', ['Pendiente', 'Confirmada', 'Completada', 'Cancelada'])->default('Pendiente');
            $table->string('meeting_type')->nullable();
            $table->text('notes')->nullable(); // Notas adicionales sobre la reunión
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_pet');
    }
};
