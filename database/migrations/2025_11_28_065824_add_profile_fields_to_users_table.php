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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('role');
            $table->string('banner')->nullable()->after('avatar');
            $table->string('phone')->nullable()->after('banner');
            $table->text('bio')->nullable()->after('phone');
            $table->json('social_links')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'banner', 'phone', 'bio', 'social_links']);
        });
    }
};
