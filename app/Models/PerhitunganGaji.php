<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganGaji extends Model
{
    use HasFactory;

    protected $table = 'perhitungan_gajis';

    protected $fillable = [
        'tanggal',
        'karyawan_id',
        'nik',
        'nama_karyawan',
        'jabatan',
        'gaji_pokok',
        'tunjangan_makan',
        'tunjangan_transportasi',
        'tunjangan_bpjs',
        'tunjangan_jkm',
        'tunjangan_jkk',
        'potongan_bpjs',
        'potongan_pph21',
        'potongan_kehadiran',
        'total_pendapatan',
        'total_potongan',
        'gaji_bersih',
    ];

    // Relasi ke model Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
