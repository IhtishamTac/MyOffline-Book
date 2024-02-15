<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_voucher');
            $table->string('deskripsi');
            $table->enum('syarat', ['Belanja Minimal', 'Tidak Bersyarat']);
            $table->integer('belanja_minimal')->nullable();
            $table->integer('potongan_harga');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
