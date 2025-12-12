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
            // Expand color columns to support rgba values like "rgba(255,255,255,0.15)"
            $table->string('link_bg_color', 100)->default('#ffffff')->change();
            $table->string('button_bg_color', 100)->default('#000000')->change();
            $table->string('link_text_color', 50)->default('#000000')->change();
            $table->string('button_text_color', 50)->default('#ffffff')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->string('link_bg_color', 20)->default('#ffffff')->change();
            $table->string('button_bg_color', 20)->default('#000000')->change();
            $table->string('link_text_color', 20)->default('#000000')->change();
            $table->string('button_text_color', 20)->default('#ffffff')->change();
        });
    }
};
