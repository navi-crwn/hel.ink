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
        Schema::table('login_histories', function (Blueprint $table) {
            $table->string('country_name')->nullable()->after('country');
            $table->string('region')->nullable()->after('city');
            $table->string('isp')->nullable()->after('provider');
            $table->boolean('is_proxy')->default(false)->after('isp');
            $table->string('proxy_type')->nullable()->after('is_proxy');
            $table->integer('proxy_confidence')->nullable()->after('proxy_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropColumn(['country_name', 'region', 'isp', 'is_proxy', 'proxy_type', 'proxy_confidence']);
        });
    }
};
