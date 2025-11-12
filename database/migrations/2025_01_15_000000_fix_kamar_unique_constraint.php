<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixKamarUniqueConstraint extends Migration
{
    public function up()
    {
        Schema::table('kamars', function (Blueprint $table) {
            // Hapus unique constraint lama
            $table->dropUnique(['nomor_kamar']);
            
            // Tambah composite unique constraint (hotel_id + nomor_kamar)
            $table->unique(['hotel_id', 'nomor_kamar']);
        });
    }

    public function down()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropUnique(['hotel_id', 'nomor_kamar']);
            $table->unique('nomor_kamar');
        });
    }
}