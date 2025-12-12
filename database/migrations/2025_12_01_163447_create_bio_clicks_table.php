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
        Schema::create('bio_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bio_link_id')->constrained()->onDelete('cascade');
            $table->timestamp('clicked_at');
            $table->string('country', 10)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('browser', 50)->nullable();
            $table->text('referrer')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->index('bio_link_id');
            $table->index('clicked_at');
            $table->index('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_clicks');
    }
};
