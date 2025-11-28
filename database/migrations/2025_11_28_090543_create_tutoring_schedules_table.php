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
        Schema::create('tutoring_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->json('proposed_slots')->nullable(); // Buyer's preferred times
            $table->datetime('confirmed_slot')->nullable(); // Seller picks one
            $table->string('external_link')->nullable(); // Zoom/Meet link
            $table->text('topic')->nullable(); // What they need help with
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoring_schedules');
    }
};
