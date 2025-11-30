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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel orders
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // Menghubungkan ke tabel users (pengirim pesan)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Isi pesan
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};