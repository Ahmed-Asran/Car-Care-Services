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
        Schema::create('enquiry_responses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->foreignId('enquiry_id')->constrained('enquiries')->onDelete('cascade');
            $table->boolean('is_admin')->nullable()->default(false);
            $table->text('content')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_responses');
    }
};
