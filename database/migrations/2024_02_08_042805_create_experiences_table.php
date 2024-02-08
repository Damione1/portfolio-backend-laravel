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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title', 100)->nullable(false);
            $table->string('subtitle', 100)->nullable(true);
            $table->text('description')->nullable(true);
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(true);
            $table->boolean('is_current')->default(false);
            $table->enum('type', ['work', 'education', 'internship', 'volunteer'])->default('work');
        });

        Schema::create('experience_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->nullable(false)->constrained('experiences');
            $table->foreignId('skill_id')->nullable(false)->constrained('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('experience_skill');
    }
};
