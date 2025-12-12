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
        Schema::create('ip_watchlist', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->integer('attempt_count')->default(1);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_watchlist');
    }
};
