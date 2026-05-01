<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('shops', function (Blueprint $table) {
        // Kita tambah kolom is_active, default-nya true (Buka)
        // Ditaruh setelah kolom close_time biar rapi
        $table->boolean('is_active')->default(true)->after('close_time');
    });
}

public function down()
{
    Schema::table('shops', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}
};
