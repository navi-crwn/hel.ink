<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds support for advanced block types: video, music, vcard, html, countdown, map, faq
     */
    public function up(): void
    {
        Schema::table('bio_links', function (Blueprint $table) {
            // Video & Music embed URL
            $table->string('embed_url', 500)->nullable()->after('thumbnail_url');
            
            // Countdown fields
            $table->timestamp('countdown_date')->nullable()->after('embed_url');
            $table->string('countdown_label', 100)->nullable()->after('countdown_date');
            
            // Map fields
            $table->text('map_address')->nullable()->after('countdown_label');
            $table->unsignedTinyInteger('map_zoom')->default(15)->after('map_address');
            
            // VCard fields
            $table->string('vcard_name', 100)->nullable()->after('map_zoom');
            $table->string('vcard_phone', 30)->nullable()->after('vcard_name');
            $table->string('vcard_email', 100)->nullable()->after('vcard_phone');
            $table->string('vcard_company', 100)->nullable()->after('vcard_email');
            
            // FAQ items (JSON array)
            $table->json('faq_items')->nullable()->after('vcard_company');
            
            // Custom HTML content
            $table->text('html_content')->nullable()->after('faq_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_links', function (Blueprint $table) {
            $table->dropColumn([
                'embed_url',
                'countdown_date',
                'countdown_label',
                'map_address',
                'map_zoom',
                'vcard_name',
                'vcard_phone',
                'vcard_email',
                'vcard_company',
                'faq_items',
                'html_content'
            ]);
        });
    }
};
