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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 100)->nullable(false);
            $table->foreignId('image_id')->nullable()->constrained('images')->onDelete('set null');
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert', 'master'])->default('beginner');
            $table->integer('order')->nullable(false)->default(0);
            $table->foreignId('user_id')->nullable(false)->constrained('users');
        });

        Schema::create('project_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable(false)->constrained('projects');
            $table->foreignId('skill_id')->nullable(false)->constrained('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_skill');
        Schema::dropIfExists('skills');
    }
};
