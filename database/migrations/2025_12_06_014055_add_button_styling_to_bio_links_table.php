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
            $table->string('btn_bg_color', 30)->nullable()->after('brand');
            $table->string('btn_text_color', 30)->nullable()->after('btn_bg_color');
            $table->string('btn_border_color', 30)->nullable()->after('btn_text_color');
            $table->boolean('btn_icon_invert')->default(true)->after('btn_border_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bio_links', function (Blueprint $table) {
            $table->dropColumn(['btn_bg_color', 'btn_text_color', 'btn_border_color', 'btn_icon_invert']);
        });
    }
};
