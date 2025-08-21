<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_status_changes', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->nullable();
            $table->string('status');
            $table->timestamps(); // created_at will log the change, updated_at not really needed but included
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_status_changes');
    }
};
