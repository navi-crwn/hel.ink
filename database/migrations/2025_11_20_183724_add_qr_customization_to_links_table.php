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
        Schema::table('links', function (Blueprint $table) {
            $table->string('qr_fg_color', 7)->default('#000000')->after('qr_code_path');
            $table->string('qr_bg_color', 7)->default('#ffffff')->after('qr_fg_color');
            $table->string('qr_logo_url')->nullable()->after('qr_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(['qr_fg_color', 'qr_bg_color', 'qr_logo_url']);
        });
    }
};
