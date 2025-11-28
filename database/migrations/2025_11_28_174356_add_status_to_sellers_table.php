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
        // Default 'pending' artinya menunggu persetujuan
        $table->string('status')->default('pending')->after('user_id'); 
    });
}

public function down(): void
{
    Schema::table('sellers', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
