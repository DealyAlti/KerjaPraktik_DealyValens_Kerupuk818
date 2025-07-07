<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermintaanBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permintaan_barang', function (Blueprint $table) {
            $table->increments('id_permintaanbarang');
            $table->unsignedInteger('id_produk');
            $table->date('tanggal_permintaan');
            $table->integer('jumlahPermintaan');
            $table->enum('status', ['Disetujui', 'Ditolak', 'Pending', 'Batal'])->default('Pending');
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
        Schema::dropIfExists('permintaan_barang');
    }
}
