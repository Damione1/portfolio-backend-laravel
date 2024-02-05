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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 100)->nullable(false);
            $table->longText('description', 8000)->nullable(false);
            $table->enum('status', ['published', 'draft', 'archived'])->default('published');
            $table->foreignId('cover_image_id')->nullable()->constrained('images')->onDelete('set null');
            $table->foreignId('user_id')->nullable(false)->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
