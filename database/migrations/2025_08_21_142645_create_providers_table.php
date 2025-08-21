<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('national_id_image')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('providers');
    }
};