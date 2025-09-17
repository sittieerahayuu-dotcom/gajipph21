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
        Schema::create('perhitungan_gajis', function (Blueprint $table) {
            $table->id();

            // Tanggal perhitungan untuk fitur generate bulanan
            $table->date('tanggal');

            // Data karyawan
           $table->unsignedBigInteger('karyawan_id')->nullable();
            $table->string('nik');
            $table->string('nama_karyawan');
            $table->string('jabatan');

            // Komponen gaji dan tunjangan dari data jabatan
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan_makan', 15, 2);
            $table->decimal('tunjangan_transportasi', 15, 2);
            $table->decimal('tunjangan_bpjs', 15, 2);
            $table->decimal('tunjangan_jkm', 15, 2);
            $table->decimal('tunjangan_jkk', 15, 2);

            // Potongan
            $table->decimal('potongan_bpjs', 15, 2);
            $table->decimal('potongan_pph21', 15, 2);
            $table->decimal('potongan_kehadiran', 15, 2); // dari jumlah alpha * nilai potongan per hari

            // Total perhitungan
            $table->decimal('total_pendapatan', 15, 2);
            $table->decimal('total_potongan', 15, 2);
            $table->decimal('gaji_bersih', 15, 2);

            $table->timestamps();

            // Foreign key relasi ke tabel karyawans
            $table->foreign('karyawan_id')->references('id')->on('karyawans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhitungan_gajis');
    }
};
