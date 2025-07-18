<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahForeignKeyPegawaiBarangMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->unsignedInteger('id_pegawai')->change();
            $table->foreign('id_pegawai')
                ->references('id_pegawai')
                ->on('pegawai')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->integer('id_pegawai')->change();
            $table->dropForeign('barang_masuk_id_pegawai_foreign');
        });
    }
}
