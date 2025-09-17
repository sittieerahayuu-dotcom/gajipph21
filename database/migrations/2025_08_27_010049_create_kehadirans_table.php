<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans')->onDelete('set null');
            $table->string('nik', 16);
            $table->string('nama_karyawan');
            $table->string('nama_jabatan')->nullable();
            $table->integer('jumlah_masuk')->default(0);
            $table->integer('jumlah_alpha')->default(0);
            $table->integer('jumlah_sakit')->default(0);
            $table->integer('jumlah_izin')->default(0);
            $table->integer('jumlah_cuti')->default(0);
            $table->timestamps();

            // Unique constraint per karyawan per tanggal
            $table->unique(['karyawan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};
