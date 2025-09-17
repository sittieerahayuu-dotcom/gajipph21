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
        Schema::create('perhitungan_pphs', function (Blueprint $table) {
            $table->id();

              // Tanggal perhitungan untuk fitur generate bulanan
            $table->date('tanggal');

            // Relasi ke data karyawan
           $table->unsignedBigInteger('karyawan_id')->nullable();
           
            $table->string('nik');
            $table->string('nama_karyawan');
            $table->string('jabatan');

            // Komponen gaji & tunjangan (dari data jabatan)
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan_makan', 15, 2);
            $table->decimal('tunjangan_transportasi', 15, 2);
            $table->decimal('tunjangan_bpjs', 15, 2); // 4% dari gapok
            $table->decimal('tunjangan_jkm', 15, 2);  // Jaminan kematian
            $table->decimal('tunjangan_jkk', 15, 2);  // Jaminan kecelakaan kerja
            $table->decimal('potongan_kehadiran', 15, 2); // dari jumlah alpha * nilai potongan per hari

            // Penghasilan Bruto 
            $table->decimal('penghasilan_bruto', 15, 2)->default(0);

            //Pengurangan
            $table->decimal('biaya_jabatan', 15, 2); // 5% dari gapok
            $table->decimal('iuran_bpjs', 15, 2);    // 1% dari gapok

            // Penghasilan Netto
            $table->decimal('netto_per_bulan', 15, 2);
            $table->decimal('netto_per_tahun', 15, 2);

            // PTKP & PKP
            $table->decimal('ptkp', 15, 2);
            $table->decimal('pkp', 15, 2);

            // PPh 21
            $table->decimal('pph21_setahun', 15, 2);
            $table->decimal('pph21_sebulan', 15, 2);

            $table->timestamps();

            // Relasi ke tabel karyawans
              $table->foreign('karyawan_id')->references('id')->on('karyawans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhitungan_pphs');
    }
};
