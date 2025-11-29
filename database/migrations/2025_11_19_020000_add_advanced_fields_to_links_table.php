<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->foreignId('folder_id')->nullable()->after('user_id')->constrained('folders')->nullOnDelete();
            $table->string('password_hash')->nullable()->after('target_url');
            $table->timestamp('expires_at')->nullable()->after('password_hash');
            $table->string('redirect_type', 3)->default('302')->after('expires_at');
            $table->string('custom_title')->nullable()->after('redirect_type');
            $table->text('custom_description')->nullable()->after('custom_title');
            $table->string('custom_image_url')->nullable()->after('custom_description');
            $table->string('qr_code_path')->nullable()->after('custom_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropConstrainedForeignId('folder_id');
            $table->dropColumn([
                'password_hash',
                'expires_at',
                'redirect_type',
                'custom_title',
                'custom_description',
                'custom_image_url',
                'qr_code_path',
            ]);
        });
    }
};
