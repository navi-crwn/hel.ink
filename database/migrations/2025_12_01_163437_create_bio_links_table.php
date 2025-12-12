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
        Schema::create('bio_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bio_page_id')->constrained()->onDelete('cascade');
            $table->string('title', 100);
            $table->text('url');
            $table->string('icon', 50)->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->timestamps();
            
            $table->index('bio_page_id');
            $table->index(['bio_page_id', 'order']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_links');
    }
};
