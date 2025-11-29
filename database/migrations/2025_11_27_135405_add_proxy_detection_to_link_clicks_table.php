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
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->boolean('is_proxy')->default(false)->after('isp');
            $table->string('proxy_type', 50)->nullable()->after('is_proxy');
            $table->tinyInteger('proxy_confidence')->nullable()->after('proxy_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropColumn(['is_proxy', 'proxy_type', 'proxy_confidence']);
        });
    }
};
