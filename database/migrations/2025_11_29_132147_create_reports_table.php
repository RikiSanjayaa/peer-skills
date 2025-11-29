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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        // Relasi ke Order (Order mana yang dilaporkan)
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        
        // Siapa yang lapor? (Pelapor)
        $table->foreignId('reporter_id')->constrained('users');
        
        // Siapa yang dilaporkan? (Terlapor)
        $table->foreignId('reported_user_id')->constrained('users');
        
        // Isi Laporan
        $table->string('reason'); // Alasan singkat (Dropdown)
        $table->text('description'); // Detail masalah
        $table->string('status')->default('pending'); // Status penanganan admin (pending/resolved)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
