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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->string('no_induk', 20)->unique(); // No. Induk Karyawan (unik)
            $table->string('nik', 16)->unique(); // NIK KTP/NPWP Karyawan (unik)
            $table->string('nama_karyawan'); // Nama Karyawan
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->onDelete('set null');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']); // Jenis Kelamin
            $table->enum('status', ['Menikah', 'Belum Menikah']); // Status Perkawinan
            $table->integer('jumlah_tanggungan_anak')->default(0); // Jumlah Tanggungan Anak
            $table->string('kode_ptkp', 10); // Kode PTKP (misalnya: TK0, K1, K2)
            $table->enum('status_karyawan', ['Aktif', 'Nonaktif']); // Status jabatan
            $table->timestamps(); // Kolom created_at dan updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
