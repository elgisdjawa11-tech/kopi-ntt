<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('orders', 'kabupaten')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('kabupaten')->after('alamat_pengiriman');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('orders', 'kabupaten')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('kabupaten');
            });
        }
    }
};