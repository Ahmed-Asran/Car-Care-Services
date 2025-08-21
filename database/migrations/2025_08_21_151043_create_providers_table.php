<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void 
{
    Schema::create('providers', function (Blueprint $table) {
        $table->id();
        $table->integer('user_id')->nullable();   // keep as plain int for now
        $table->string('verification_status')->default('pending'); // later use ENUM
        $table->integer('national_id_image_id')->nullable();
        $table->integer('location_id')->nullable();
        $table->timestamps();
    });
}

public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};