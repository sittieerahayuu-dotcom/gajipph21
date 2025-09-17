<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganPph extends Model
{
    use HasFactory;

    // Jika kamu tetap menggunakan nama tabel 'perhitungan_pphs' (default Laravel), ini tidak perlu ditulis:
    // protected $table = 'perhitungan_pphs';

    // Kalau kamu ubah ke 'perhitungan_pph', baru tambahkan:
    // protected $table = 'perhitungan_pph';

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
        'potongan_kehadiran',
        'penghasilan_bruto',
        'biaya_jabatan',
        'iuran_bpjs',
        'netto_per_bulan',
        'netto_per_tahun',
        'ptkp',
        'pkp',
        'pph21_setahun',
        'pph21_sebulan',
    ];

    // Relasi ke model Karyawan
    
    // Di model PerhitunganPph.php
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
