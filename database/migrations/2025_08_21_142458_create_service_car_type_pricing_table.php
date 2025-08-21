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
        Schema::create('service_car_type_pricing', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('service_id');
            $table->integer('car_type_id')->index('car_type_id');
            $table->decimal('price', 10);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->unique(['service_id', 'car_type_id'], 'unique_service_car_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_car_type_pricing');
    }
};
