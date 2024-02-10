<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('description', 'content');
            $table->string('excerpt')->nullable();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('excerpt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('content', 'description');
            $table->dropColumn('excerpt');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
    }
};
