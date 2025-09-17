<?php

namespace App\Http\Controllers;

use App\Models\PotonganGaji;
use Illuminate\Http\Request;

class PotonganGajiController extends Controller
{
    // Menampilkan daftar potongan gaji dengan pencarian dan paginasi
    public function index(Request $request)
    {
        // Ambil jumlah per halaman dari parameter 'entries', default 5
        $perPage = $request->input('entries', 5);

        // Validasi entries agar hanya nilai tertentu yang diperbolehkan
        if (!in_array((int) $perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }

        $search = $request->input('search');

        $potongans = PotonganGaji::when($search, function ($query, $search) {
            return $query->where('jenis_potongan', 'like', '%' . $search . '%');
        })
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        return view('potongangaji.index', compact('potongans'));
    }

    // Menampilkan form tambah potongan gaji
    public function create()
    {
        return view('potongangaji.create');
    }

    // Menyimpan potongan gaji baru ke database
  public function store(Request $request)
{
    $request->validate([
        'jenis_potongan' => 'required|string|max:100',
        'nilai_potongan' => 'required',
    ]);

    // Hapus titik ribuan
    $nilaiBersih = str_replace('.', '', $request->nilai_potongan);

    PotonganGaji::create([
        'jenis_potongan' => $request->jenis_potongan,
        'nilai_potongan' => $nilaiBersih,
    ]);

    return redirect()->route('potongangaji.index')->with('success', 'Potongan gaji berhasil ditambahkan.');
}


    // Menampilkan form edit potongan gaji
  public function edit($id)
    {
        $potongan = PotonganGaji::findOrFail($id); // pastikan data ditemukan
        return view('potongangaji.edit', compact('potongan'));
    }

    // Memperbarui data potongan gaji
 public function update(Request $request, $id)
{
    $request->validate([
        'jenis_potongan' => 'required|string|max:100',
        'nilai_potongan' => 'required',
    ]);

    $nilaiBersih = str_replace('.', '', $request->nilai_potongan);

    $potongan = PotonganGaji::findOrFail($id);
    $potongan->update([
        'jenis_potongan' => $request->jenis_potongan,
        'nilai_potongan' => $nilaiBersih,
    ]);

    return redirect()->route('potongangaji.index')->with('success', 'Potongan gaji berhasil diperbarui.');
}


    // Menghapus potongan gaji
    public function destroy(PotonganGaji $potongangaji)
    {
        $potongangaji->delete();

        return redirect()->route('potongangaji.index')->with('success', 'Potongan gaji berhasil dihapus!');
    }
}
