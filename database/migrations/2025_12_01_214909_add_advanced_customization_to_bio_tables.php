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
        // Add design customization columns to bio_pages
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->string('layout', 50)->default('default')->after('bio');
            $table->string('background_type', 20)->default('color')->after('layout'); // color, gradient, image
            $table->text('background_value')->nullable()->after('background_type'); // hex color, gradient values, or image URL
            $table->string('text_color', 20)->default('#000000')->after('background_value');
            $table->string('font_family', 50)->default('default')->after('text_color');
            $table->string('block_shape', 20)->default('rounded')->after('font_family'); // rounded, square, pill
            $table->string('block_shadow', 20)->default('sm')->after('block_shape'); // none, sm, md, lg
            $table->string('social_icon_color', 20)->nullable()->after('block_shadow');
            $table->string('social_placement', 20)->default('bottom')->after('social_icon_color'); // top, bottom
        });
        // Add block type and content columns to bio_links (rename conceptually to blocks)
        Schema::table('bio_links', function (Blueprint $table) {
            $table->string('type', 20)->default('link')->after('bio_page_id'); // link, image, text
            $table->text('content')->nullable()->after('url'); // For text blocks or rich content
            $table->text('description')->nullable()->after('content'); // Additional description
            $table->integer('link_id')->unsigned()->nullable()->after('description'); // FK to links table for short links
            // Make url nullable since image/text blocks may not have URLs
            $table->string('url', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->dropColumn([
                'layout',
                'background_type',
                'background_value',
                'text_color',
                'font_family',
                'block_shape',
                'block_shadow',
                'social_icon_color',
                'social_placement',
            ]);
        });
        Schema::table('bio_links', function (Blueprint $table) {
            $table->dropColumn(['type', 'content', 'description', 'link_id']);
            $table->string('url', 500)->nullable(false)->change();
        });
    }
};
