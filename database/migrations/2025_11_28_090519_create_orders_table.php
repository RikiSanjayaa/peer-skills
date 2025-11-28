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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('gig_id')->constrained('gigs')->onDelete('cascade');
            $table->enum('type', ['standard', 'tutoring'])->default('standard');
            $table->text('requirements');
            $table->decimal('price', 10, 2)->nullable(); // Set by seller when quoting
            $table->integer('delivery_days')->nullable(); // Set by seller when quoting
            $table->enum('status', [
                'pending',           // Buyer submitted, waiting for seller quote
                'quoted',            // Seller set price, waiting for buyer
                'accepted',          // Buyer accepted, seller working
                'delivered',         // Seller submitted deliverables
                'revision_requested', // Buyer wants changes
                'completed',         // Done
                'cancelled',         // Cancelled before acceptance
                'declined'           // Buyer declined quote
            ])->default('pending');
            $table->text('seller_notes')->nullable(); // Seller can add notes with quote
            $table->timestamp('quoted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
