<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    // Nama tabel (opsional, kalau default plural dari model nama 'karyawans' sudah benar, ini boleh dihapus)
    protected $table = 'karyawans';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'no_induk',
        'nik',
        'nama_karyawan', // Sesuai dengan nama kolom di database
        'jabatan_id',
        'jenis_kelamin',
        'status',
        'jumlah_tanggungan_anak',
        'kode_ptkp',
        'status_karyawan',
    ];

    /**
     * Relasi ke tabel jabatans
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
        return $this->belongsTo(Jabatan::class);
    }


    /**
     * Relasi ke tabel kehadirans
     */
    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class, 'karyawan_id');
    }

    public function perhitunganPphs()
    {
        return $this->hasMany(PerhitunganPph::class);
    }
    // Dalam model Karyawan
    public function perhitunganGaji()
    {
        return $this->hasMany(PerhitunganGaji::class);
    }

    public static function generateNoInduk()
    {
        $tahun = date('y'); // 2 digit tahun (e.g., 25)
        $bulan = date('m'); // 2 digit bulan (e.g., 07)
        $prefix = 'LSW-' . $tahun . $bulan . '-';

        $last = self::where('no_induk', 'like', $prefix . '%')
            ->orderBy('no_induk', 'desc')
            ->first();

        $lastNumber = $last ? intval(substr($last->no_induk, -3)) + 1 : 1;
        return $prefix . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
    }
}
