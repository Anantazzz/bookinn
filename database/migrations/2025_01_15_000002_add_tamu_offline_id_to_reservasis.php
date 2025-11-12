<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTamuOfflineIdToReservasis extends Migration
{
    public function up()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->unsignedBigInteger('tamu_offline_id')->nullable()->after('user_id');
            $table->foreign('tamu_offline_id')->references('id')->on('tamu_offline')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropForeign(['tamu_offline_id']);
            $table->dropColumn('tamu_offline_id');
        });
    }
}