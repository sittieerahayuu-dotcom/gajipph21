<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'tanggal', 
        'karyawan_id',
        'nik',
        'nama_karyawan',
        'nama_jabatan',
        'jumlah_masuk',
        'jumlah_alpha',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_cuti',
    ];

    /**
     * Relasi ke model Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
    // Dalam model Karyawan
public function perhitunganGaji()
{
    return $this->hasMany(PerhitunganGaji::class);
}

}
