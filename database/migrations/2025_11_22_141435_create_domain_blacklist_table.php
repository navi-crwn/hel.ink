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
        Schema::create('domain_blacklist', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->enum('match_type', ['exact', 'wildcard'])->default('exact');
            $table->string('category')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('domain');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_blacklist');
    }
};
