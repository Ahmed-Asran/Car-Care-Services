<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_car_id')->nullable();
            $table->integer('provider_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->decimal('location_latitude', 10, 7)->nullable();
            $table->decimal('location_longitude', 10, 7)->nullable();
            $table->decimal('distance', 8, 2)->nullable();
            $table->decimal('service_price', 10, 2)->nullable();
            $table->decimal('distance_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('status')->default('initiated');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
