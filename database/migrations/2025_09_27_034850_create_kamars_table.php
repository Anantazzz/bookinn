<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKamarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('nomor_kamar')->unique();
            $table->string('tipe_kamar');
            $table->decimal('harga', 10, 2);
            $table->enum('status', ['tersedia' , 'booking'])->default('tersedia');
            $table->integer('kapasitas');             
            $table->integer('jumlah_bed');
            $table->boolean('internet')->default(true);
            $table->string('gambar')->nullable();      
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
        Schema::dropIfExists('kamars');
    }
}
