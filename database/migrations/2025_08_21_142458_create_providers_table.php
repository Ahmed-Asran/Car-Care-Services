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
        Schema::create('providers', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->index('idx_providers_status');
            $table->integer('national_id_image_id')->nullable()->index('national_id_image_id');
            $table->integer('location_id')->nullable()->index('location_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
