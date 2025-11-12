<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveHargaFromTipeKamars extends Migration
{
    public function up()
    {
        Schema::table('tipe_kamars', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }

    public function down()
    {
        Schema::table('tipe_kamars', function (Blueprint $table) {
            $table->decimal('harga', 10, 2)->after('nama_tipe');
        });
    }
}