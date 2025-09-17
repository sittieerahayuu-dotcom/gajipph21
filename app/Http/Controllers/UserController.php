<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Menangani pencarian berdasarkan input 'search'
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;

            // Mengecek apakah pencarian adalah role dan mengubahnya menjadi angka
            if (strtolower($searchTerm) == 'staff penggajian') {
                $searchTerm = 1;
            } elseif (strtolower($searchTerm) == 'direktur') {
                $searchTerm = 2;
            } elseif (strtolower($searchTerm) == 'karyawan') {
                $searchTerm = 3;
            }

            // Mencari berdasarkan id, nama, email, atau role
            $query->where('id', 'like', '%' . $searchTerm . '%')
                ->orWhere('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('role', 'like', '%' . $searchTerm . '%');
        }

        // Ambil nilai entries dari request (default 5)
        $perPage = $request->input('entries', 5);

        // Validasi agar hanya menerima angka yang diperbolehkan
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }

        // Ambil data pengguna dengan pagination
        $users = $query->paginate($perPage);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:1,2,3', // Tambahkan role 3 (Karyawan)
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|string|email|max:50|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:1,2,3', // Tambahkan validasi role di update
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil di update.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
