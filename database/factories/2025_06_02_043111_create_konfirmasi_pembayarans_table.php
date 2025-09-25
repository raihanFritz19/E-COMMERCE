<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateKonfirmasiPembayaransTable extends Migration
{
    public function up()
    {
        Schema::create('konfirmasi_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_penyetor');
            $table->string('bank');
            $table->decimal('jumlah', 10, 2);
            $table->string('bukti');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('konfirmasi_pembayarans');
    }
}
