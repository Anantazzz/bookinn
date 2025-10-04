<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTipeKamarFromKamarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamars', function (Blueprint $table) {
            
            $table->dropColumn('tipe_kamar'); // hapus kolom lama

            // tambahin foreign key baru
            $table->foreignId('tipe_kamar_id')
              ->constrained('tipe_kamars')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamars', function (Blueprint $table) {
             $table->string('tipe_kamar')->nullable();
             $table->dropConstrainedForeignId('tipe_kamar_id');
        });
    }
}
