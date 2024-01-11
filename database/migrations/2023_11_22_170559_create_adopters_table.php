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
        Schema::create('adopters', function (Blueprint $table) {
            $table->id();
            $table->boolean('experience');
            $table->boolean('type_of_house');
            $table->boolean('other_pet');
            $table->integer('quantity');
            $table->string('adopter_profile_image')->nullable();
            $table->string('adopter_cover_image')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adopters');
    }
};
