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
        Schema::create('settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('logo_id')->nullable()->index('logo_id');
            $table->integer('about_image_id')->nullable()->index('about_image_id');
            $table->text('about_description')->nullable();
            $table->decimal('price_per_km', 10)->nullable()->default(0);
            $table->text('terms_and_conditions')->nullable();
            $table->string('facebook_url', 500)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('primary_phone_number', 20)->nullable();
            $table->string('secondary_phone_number', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
