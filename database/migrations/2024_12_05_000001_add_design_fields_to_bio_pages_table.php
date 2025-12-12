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
            // New design fields
            if (! Schema::hasColumn('bio_pages', 'background_color')) {
                $table->string('background_color', 20)->nullable()->after('background_value');
            }
            if (! Schema::hasColumn('bio_pages', 'background_gradient')) {
                $table->string('background_gradient', 255)->nullable()->after('background_color');
            }
            if (! Schema::hasColumn('bio_pages', 'button_bg_color')) {
                $table->string('button_bg_color', 50)->nullable()->after('link_text_color');
            }
            if (! Schema::hasColumn('bio_pages', 'button_text_color')) {
                $table->string('button_text_color', 20)->nullable()->after('button_bg_color');
            }
            if (! Schema::hasColumn('bio_pages', 'button_shape')) {
                $table->enum('button_shape', ['rounded', 'square', 'pill', 'soft'])->default('rounded')->after('button_text_color');
            }
            if (! Schema::hasColumn('bio_pages', 'button_shadow')) {
                $table->enum('button_shadow', ['none', 'soft', 'medium', 'hard'])->default('medium')->after('button_shape');
            }
            if (! Schema::hasColumn('bio_pages', 'socials')) {
                $table->json('socials')->nullable()->after('social_links');
            }
            if (! Schema::hasColumn('bio_pages', 'socials_position')) {
                $table->enum('socials_position', ['top', 'bottom'])->default('top')->after('socials');
            }
            if (! Schema::hasColumn('bio_pages', 'is_public')) {
                $table->boolean('is_public')->default(true)->after('is_published');
            }
            if (! Schema::hasColumn('bio_pages', 'show_in_directory')) {
                $table->boolean('show_in_directory')->default(false)->after('is_public');
            }
            if (! Schema::hasColumn('bio_pages', 'hide_branding')) {
                $table->boolean('hide_branding')->default(false)->after('show_in_directory');
            }
            if (! Schema::hasColumn('bio_pages', 'google_analytics_id')) {
                $table->string('google_analytics_id', 50)->nullable()->after('hide_branding');
            }
            if (! Schema::hasColumn('bio_pages', 'facebook_pixel_id')) {
                $table->string('facebook_pixel_id', 50)->nullable()->after('google_analytics_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_pages', function (Blueprint $table) {
            $columns = [
                'background_color',
                'background_gradient',
                'button_bg_color',
                'button_text_color',
                'button_shape',
                'button_shadow',
                'socials',
                'socials_position',
                'is_public',
                'show_in_directory',
                'hide_branding',
                'google_analytics_id',
                'facebook_pixel_id',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('bio_pages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
