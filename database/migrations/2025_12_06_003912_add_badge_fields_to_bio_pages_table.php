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
            $table->string('badge', 20)->nullable()->after('avatar_shape'); // verified, star, crown, fire
            $table->string('badge_color', 20)->nullable()->after('badge'); // #hex color
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_pages', function (Blueprint $table) {
            $table->dropColumn(['badge', 'badge_color']);
        });
    }
};
