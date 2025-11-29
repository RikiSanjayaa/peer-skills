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
        Schema::table('sellers', function (Blueprint $table) {
            // Status sudah ada, hanya tambah kolom lainnya
            // Alasan penolakan (jika ditolak)
            $table->text('rejection_reason')->nullable()->after('status');
            // Tanggal ditolak
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            // Admin yang memproses
            $table->foreignId('reviewed_by')->nullable()->after('rejected_at')->constrained('users')->nullOnDelete();
            // Tanggal direview
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['rejection_reason', 'rejected_at', 'reviewed_by', 'reviewed_at']);
        });
    }
};
