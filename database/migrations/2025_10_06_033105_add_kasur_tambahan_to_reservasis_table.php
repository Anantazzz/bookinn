<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKasurTambahanToReservasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->boolean('kasur_tambahan')->default(0)->after('status');
            $table->decimal('total_harga', 10, 2)->default(0)->after('kasur_tambahan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservasis', function (Blueprint $table) {
              $table->dropColumn(['kasur_tambahan', 'total_harga']);
        });
    }
}
