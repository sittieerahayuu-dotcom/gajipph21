<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Models\Karyawan;

class JabatanController extends Controller
{
    // Menampilkan semua data jabatan
    public function index(Request $request)
    {
        // Ambil jumlah per halaman, default 5
        $perPage = $request->input('entries', 5);

        // Validasi nilai entries agar tidak aneh-aneh
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }

        // Ambil kata kunci pencarian jika ada
        $search = $request->input('search');

        // Query dengan filter pencarian di semua kolom yang relevan
        $jabatans = Jabatan::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_jabatan', 'like', '%' . $search . '%')
                    ->orWhere('gaji_pokok', 'like', '%' . $search . '%')
                    ->orWhere('tunjangan_makan', 'like', '%' . $search . '%')
                    ->orWhere('tunjangan_transportasi', 'like', '%' . $search . '%')
                    ->orWhere('tunjangan_bpjs_kesehatan', 'like', '%' . $search . '%')
                    ->orWhere('tunjangan_jkm', 'like', '%' . $search . '%')
                    ->orWhere('tunjangan_jkk', 'like', '%' . $search . '%')
                    ->orWhere('biaya_jabatan', 'like', '%' . $search . '%');
            });
        })

            // Menambahkan pengurutan berdasarkan id
            ->orderBy('id', 'asc') // Urutkan berdasarkan ID
            ->paginate($perPage);

        // Kirim data ke view
        return view('jabatan.index', compact('jabatans'));
    }


    // Menampilkan form tambah jabatan
    public function create()
    {
        return view('jabatan.create');
    }

    // Menyimpan data jabatan baru
    public function store(Request $request)
{
    // Hapus tanda titik (.) agar bisa divalidasi sebagai numeric
    $request->merge([
        'gaji_pokok' => str_replace('.', '', $request->gaji_pokok),
        'tunjangan_makan' => str_replace('.', '', $request->tunjangan_makan),
        'tunjangan_transportasi' => str_replace('.', '', $request->tunjangan_transportasi),
    ]);

    // Validasi input
    $request->validate([
        'nama_jabatan' => 'required|string|max:255',
        'gaji_pokok' => 'required|numeric|min:0',
        'tunjangan_makan' => 'required|numeric|min:0',
        'tunjangan_transportasi' => 'required|numeric|min:0',
    ]);

    Jabatan::create([
        'nama_jabatan' => $request->nama_jabatan,
        'gaji_pokok' => $request->gaji_pokok,
        'tunjangan_makan' => $request->tunjangan_makan,
        'tunjangan_transportasi' => $request->tunjangan_transportasi,
        'tunjangan_bpjs_kesehatan' => $request->gaji_pokok * 0.04,
        'tunjangan_jkm' => $request->gaji_pokok * 0.003,
        'tunjangan_jkk' => $request->gaji_pokok * 0.0054,
        'biaya_jabatan' => $request->gaji_pokok * 0.05,
    ]);

    return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil ditambahkan!');
}


    // Menampilkan form edit jabatan
    public function edit($id)
    {
        // Ambil data jabatan berdasarkan id
        $jabatan = Jabatan::findOrFail($id);
        return view('jabatan.edit', compact('jabatan'));
    }

    // Memperbarui data jabatan
    public function update(Request $request, $id)
{
    // Hapus tanda titik (.) dari input rupiah
    $request->merge([
        'gaji_pokok' => str_replace('.', '', $request->gaji_pokok),
        'tunjangan_makan' => str_replace('.', '', $request->tunjangan_makan),
        'tunjangan_transportasi' => str_replace('.', '', $request->tunjangan_transportasi),
    ]);

    $request->validate([
        'nama_jabatan' => 'required|string|max:40',
        'gaji_pokok' => 'required|numeric|min:0',
        'tunjangan_makan' => 'required|numeric|min:0',
        'tunjangan_transportasi' => 'required|numeric|min:0',
    ]);

    $jabatan = Jabatan::findOrFail($id);

    $jabatan->update([
        'nama_jabatan' => $request->nama_jabatan,
        'gaji_pokok' => $request->gaji_pokok,
        'tunjangan_makan' => $request->tunjangan_makan,
        'tunjangan_transportasi' => $request->tunjangan_transportasi,
        'tunjangan_bpjs_kesehatan' => $request->gaji_pokok * 0.04,
        'tunjangan_jkm' => $request->gaji_pokok * 0.003,
        'tunjangan_jkk' => $request->gaji_pokok * 0.0054,
        'biaya_jabatan' => $request->gaji_pokok * 0.05,
    ]);

    // Ambil semua karyawan yang menggunakan jabatan ini
    $karyawans = Karyawan::where('jabatan_id', $jabatan->id)->get();

    // Re-hit PPh 21 dan gaji karyawan jika diperlukan
    // foreach ($karyawans as $karyawan) {
    //     (new PerhitunganPphController())->hitungPph21($karyawan);
    //     (new PerhitunganGajiController())->hitungGaji($karyawan);
    // }

    return redirect()->route('jabatans.index')->with('success', 'Jabatan dan data PPh 21 karyawan berhasil diperbarui!');
}



    // Menghapus data jabatan
    public function destroy($id)
    {
        // Cek apakah jabatan ada
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil dihapus!');
    }
}
