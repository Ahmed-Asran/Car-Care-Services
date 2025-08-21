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
        Schema::create('customer_cars', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->index('idx_customer_cars_user');
            $table->integer('car_type_id')->index('car_type_id');
            $table->string('car_license', 100);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_cars');
    }
};
