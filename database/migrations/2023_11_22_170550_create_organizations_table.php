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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_rut');
            $table->string('organization_cv', 1);
            $table->string('organization_name');
            $table->string('business_name');
            $table->unsignedBigInteger('organization_phone')->nullable();
            $table->string('organization_email')->unique();
            $table->string('organization_state')->nullable();
            $table->string('organization_province')->nullable();
            $table->string('organization_city')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('organization_profile_image')->nullable();
            $table->string('organization_cover_image')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
