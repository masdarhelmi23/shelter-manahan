<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Menambahkan kolom jam operasional
            $table->time('open_time')->nullable()->after('instagram');
            $table->time('close_time')->nullable()->after('open_time');
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['open_time', 'close_time']);
        });
    }
};