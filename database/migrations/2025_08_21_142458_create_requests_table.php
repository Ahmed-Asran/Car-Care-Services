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
        Schema::create('requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->foreignId('customer_car_id')->constrained('customer_cars')->onDelete('cascade');
            $table->foreignId('provider_id')->nullable()->constrained('providers')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->decimal('location_latitude', 10, 8)->nullable();
            $table->decimal('location_longitude', 11, 8)->nullable();
            $table->decimal('distance', 10)->nullable();
            $table->decimal('service_price', 10)->nullable();
            $table->decimal('distance_price', 10)->nullable();
            $table->decimal('total_price', 10)->nullable();
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending')->index('idx_requests_status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
