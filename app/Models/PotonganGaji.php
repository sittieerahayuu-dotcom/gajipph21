<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganGaji extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai dengan yang ada di database
    protected $table = 'potongan_gajis';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'jenis_potongan',
        'nilai_potongan',
    ];

    // Jika kolom timestamps tidak diperlukan, bisa diatur di sini
    public $timestamps = true;

    // Dalam model Karyawan
public function perhitunganGaji()
{
    return $this->hasMany(PerhitunganGaji::class);
}

}
