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
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->string('title_color', 20)->default('#000000')->after('text_color');
            $table->string('bio_color', 20)->default('#64748b')->after('title_color');
            $table->string('link_bg_color', 20)->default('#ffffff')->after('bio_color');
            $table->string('link_text_color', 20)->default('#000000')->after('link_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->dropColumn(['title_color', 'bio_color', 'link_bg_color', 'link_text_color']);
        });
    }
};
