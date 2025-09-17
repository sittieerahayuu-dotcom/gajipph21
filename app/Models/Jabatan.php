<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'jabatans';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
        'tunjangan_makan',
        'tunjangan_transportasi',
        'tunjangan_bpjs_kesehatan',
        'tunjangan_jkm',
        'tunjangan_jkk',
        'biaya_jabatan',
    ];

    public function perhitunganPphs()
{
    return $this->hasMany(PerhitunganPph::class);
}


}
