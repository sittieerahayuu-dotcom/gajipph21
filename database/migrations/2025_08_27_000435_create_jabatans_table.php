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
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan'); // Nama jabatan
            $table->decimal('gaji_pokok', 15, 2); // Gaji pokok
            $table->decimal('tunjangan_makan', 15, 2); // Tunjangan makan
            $table->decimal('tunjangan_transportasi', 15, 2); // Tunjangan transportasi
            $table->decimal('tunjangan_bpjs_kesehatan', 15, 2); // Tunjangan BPJS Kesehatan
            $table->decimal('tunjangan_jkm', 15, 2); // Tunjangan JKM (Jaminan Kematian)
            $table->decimal('tunjangan_jkk', 15, 2); // Tunjangan JKK (Jaminan Kecelakaan Kerja)
            $table->decimal('biaya_jabatan', 15, 2); // Biaya Jabatan
            $table->timestamps(); // created_at, updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
