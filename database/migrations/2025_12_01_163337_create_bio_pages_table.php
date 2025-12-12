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
        Schema::create('bio_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug', 50)->unique();
            $table->string('title', 100);
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('theme', 50)->default('default');
            $table->json('social_links')->nullable();
            $table->string('seo_title', 200)->nullable();
            $table->text('seo_description')->nullable();
            $table->text('custom_css')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('slug');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_pages');
    }
};
