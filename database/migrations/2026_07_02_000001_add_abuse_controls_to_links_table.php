<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            // Creator IP for forensics/takedown on anonymous links.
            $table->string('created_ip', 45)->nullable()->after('user_id');
            // Auto-moderation review trail.
            $table->timestamp('flagged_at')->nullable()->after('status');
            $table->string('flag_reason')->nullable()->after('flagged_at');
            $table->index('flagged_at');
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex(['flagged_at']);
            $table->dropColumn(['created_ip', 'flagged_at', 'flag_reason']);
        });
    }
};
