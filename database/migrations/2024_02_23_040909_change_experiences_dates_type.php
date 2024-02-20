<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->timestamp('start_date')->change();
            $table->timestamp('end_date')->nullable()->change();
            $table->dropColumn('is_current');
        });
    }

    public function down()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->date('start_date')->change();
            $table->date('end_date')->nullable()->change();
            $table->boolean('is_current')->default(false);
        });
    }
};
