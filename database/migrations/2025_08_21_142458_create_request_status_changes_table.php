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
        Schema::create('request_status_changes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('request_id')->index('request_id');
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_status_changes');
    }
};
