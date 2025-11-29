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
            $table->string('last_login_city')->nullable()->after('last_login_country');
            $table->string('last_login_provider')->nullable()->after('last_login_city');
            $table->string('last_login_device')->nullable()->after('last_login_provider');
            $table->string('last_login_browser')->nullable()->after('last_login_device');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_city', 'last_login_provider', 'last_login_device', 'last_login_browser']);
        });
    }
};
