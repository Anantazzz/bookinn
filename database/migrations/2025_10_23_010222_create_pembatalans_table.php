<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembatalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembatalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')->constrained('reservasis')->onDelete('cascade');
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->string('alasan');
            $table->enum('status', ['batal'])->default('batal'); 
            $table->decimal('jumlah_refund', 10, 2)->nullable(); 
            $table->timestamp('tanggal_pembatalan')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembatalans');
    }
}
