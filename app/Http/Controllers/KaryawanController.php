<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    // Tampilkan semua karyawan
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 5);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }

        $search = $request->input('search');

        $query = Karyawan::with('jabatan')->when($search, function ($q) use ($search) {
            $q->where('nik', 'like', '%' . $search . '%')
                ->orWhere('no_induk', 'like', '%' . $search . '%')
                ->orWhere('nama_karyawan', 'like', '%' . $search . '%')
                ->orWhere('jenis_kelamin', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('kode_ptkp', 'like', '%' . $search . '%')
                ->orWhere('status_karyawan', 'like', '%' . $search . '%');
        });

        $karyawans = $query->orderBy('id', 'asc')->paginate($perPage);

        return view('karyawan.index', compact('karyawans'));
    }

    // Form tambah
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.create', compact('jabatans'));
    }

    // Simpan data baru + buat user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric|unique:karyawans,nik',
            'nama_karyawan' => 'required|string|max:40',
            'jabatan_id' => 'required|exists:jabatans,id',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'status' => 'required|in:Menikah,Belum Menikah',
            'jumlah_tanggungan_anak' => 'required|integer|min:0|max:3',
            'status_karyawan' => 'required|in:Aktif,Nonaktif',
        ]);

        // PTKP & no induk
        $validated['kode_ptkp'] = $this->generateKodePtkp($validated['status'], $validated['jumlah_tanggungan_anak']);
        $validated['no_induk'] = Karyawan::generateNoInduk();

        // Simpan karyawan
        $karyawan = Karyawan::create($validated);

        // Buat akun user otomatis
        User::create([
            'name'     => $karyawan->nama_karyawan,
            'email'    => $karyawan->nik . '@dummy.local',
            'password' => Hash::make($karyawan->nik),
            'role'     => 3,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan & akun login dibuat.');
    }

    // Form edit
    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $jabatans = Jabatan::all();

        return view('karyawan.edit', compact('karyawan', 'jabatans'));
    }

    // Update data + sinkron ke users
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric|unique:karyawans,nik,' . $id,
            'nama_karyawan' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'status' => 'required|in:Menikah,Belum Menikah',
            'jumlah_tanggungan_anak' => 'required|integer|min:0|max:3',
            'status_karyawan' => 'required|in:Aktif,Nonaktif',
        ]);

        $validated['kode_ptkp'] = $this->generateKodePtkp($validated['status'], $validated['jumlah_tanggungan_anak']);

        $karyawan = Karyawan::findOrFail($id);
        $oldNik = $karyawan->nik;

        // Update data karyawan
        $karyawan->update($validated);

        // Sinkronisasi ke tabel users
        $user = User::where('email', $oldNik . '@dummy.local')->first();
        if ($user) {
            $user->update([
                'name'     => $karyawan->nama_karyawan,
                'email'    => $karyawan->nik . '@dummy.local',
                'password' => Hash::make($karyawan->nik), // reset password = nik baru
            ]);
        }

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan & akun login berhasil diperbarui.');
    }

    // Hapus
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        // Hapus user terkait juga
        $user = User::where('email', $karyawan->nik . '@dummy.local')->first();
        if ($user) {
            $user->delete();
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan & akun login berhasil dihapus.');
    }

    // Helper PTKP
    private function generateKodePtkp($status, $tanggungan)
    {
        if ($status === 'Menikah') {
            return 'K/' . min($tanggungan, 3);
        } else {
            return 'T/0';
        }
    }
}
