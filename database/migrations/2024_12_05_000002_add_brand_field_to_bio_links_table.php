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
        Schema::table('bio_links', function (Blueprint $table) {
            if (! Schema::hasColumn('bio_links', 'brand')) {
                $table->string('brand', 50)->nullable()->after('thumbnail_url');
            }
            if (! Schema::hasColumn('bio_links', 'settings')) {
                $table->json('settings')->nullable()->after('brand');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_links', function (Blueprint $table) {
            if (Schema::hasColumn('bio_links', 'brand')) {
                $table->dropColumn('brand');
            }
            if (Schema::hasColumn('bio_links', 'settings')) {
                $table->dropColumn('settings');
            }
        });
    }
};
