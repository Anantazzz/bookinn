<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankAndAtasNamaToPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
             $table->enum('bank', ['mandiri', 'bca', 'bri', 'bni'])->nullable()->after('metode_bayar');
             $table->string('atas_nama')->nullable()->after('bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
              $table->dropColumn(['bank', 'atas_nama']);
        });
    }
}
