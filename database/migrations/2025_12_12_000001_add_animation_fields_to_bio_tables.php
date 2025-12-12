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
        // Add global animation settings to bio_pages
        Schema::table('bio_pages', function (Blueprint $table) {
            // Hover effect for all buttons (global)
            $table->string('hover_effect', 30)->nullable()->default('none');
            // Background animation effect
            $table->string('background_animation', 30)->nullable()->default('none');
        });
        // Add per-block animation settings to bio_links
        Schema::table('bio_links', function (Blueprint $table) {
            // Entrance animation when page loads
            $table->string('entrance_animation', 30)->nullable()->default('none');
            // Attention animation (CTA pulse, shake, etc)
            $table->string('attention_animation', 30)->nullable()->default('none');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->dropColumn(['hover_effect', 'background_animation']);
        });
        Schema::table('bio_links', function (Blueprint $table) {
            $table->dropColumn(['entrance_animation', 'attention_animation']);
        });
    }
};
