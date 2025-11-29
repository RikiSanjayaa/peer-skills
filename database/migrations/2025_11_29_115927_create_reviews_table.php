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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        
        // Relasi Penting
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Supaya 1 order cuma bisa 1 review
        $table->foreignId('gig_id')->constrained()->onDelete('cascade');   // Gig mana yang direview
        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // Buyer
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');   // Seller (biar gampang hitung rating seller)
        
        // Isi Review
        $table->unsignedTinyInteger('rating'); // Angka 1 sampai 5
        $table->text('comment')->nullable();   // Komentar teks (opsional)
        
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('reviews');
}
};
