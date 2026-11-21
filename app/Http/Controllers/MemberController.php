<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Tampilkan data anggota perpustakaan dengan fitur pencarian dan filter.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian: nama, NIM / ID anggota, email, dan program studi
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(member_id) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(department) LIKE ?', ["%{$search}%"]);
            });
        }

        // Filter Status Anggota
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Urutkan anggota dari yang terbaru
        $members = $query->orderBy('id', 'desc')->get();

        // Metrik Ringkasan
        $totalMembers = User::count();
        $activeMembers = User::where('status', 'Aktif')->count();
        $totalStudents = User::where(function ($q) {
            $q->where('role', 'Anggota')
              ->orWhereNotNull('department');
        })->count();

        // Daftar program studi unik untuk referensi
        $departments = User::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->pluck('department');

        return view('members.index', compact(
            'members',
            'totalMembers',
            'activeMembers',
            'totalStudents',
            'departments'
        ));
    }

    /**
     * Simpan data anggota perpustakaan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'string', 'max:50', 'unique:users,member_id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string', Rule::in(['Aktif', 'Nonaktif'])],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'member_id.required' => 'NIM / Nomor Induk Anggota wajib diisi.',
            'member_id.unique' => 'NIM / Nomor Induk Anggota ini sudah terdaftar.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'status.required' => 'Status anggota wajib dipilih.',
            'status.in' => 'Status anggota yang dipilih tidak valid.',
        ]);

        $validated['role'] = 'Anggota';
        $validated['password'] = Hash::make('password123');

        $user = User::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota perpustakaan "' . $user->name . '" berhasil didaftarkan dengan password default "password123".');
    }

    /**
     * Perbarui data anggota perpustakaan.
     */
    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'string', 'max:50', Rule::unique('users', 'member_id')->ignore($member->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->id)],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string', Rule::in(['Aktif', 'Nonaktif'])],
            'reset_password' => ['nullable'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'member_id.required' => 'NIM / Nomor Induk Anggota wajib diisi.',
            'member_id.unique' => 'NIM / Nomor Induk Anggota sudah digunakan anggota lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh anggota lain.',
            'status.required' => 'Status anggota wajib dipilih.',
            'status.in' => 'Status anggota yang dipilih tidak valid.',
        ]);

        if ($request->boolean('reset_password')) {
            $validated['password'] = Hash::make('password123');
        }

        unset($validated['reset_password']);

        $member->update($validated);

        $message = 'Data anggota "' . $member->name . '" berhasil diperbarui.';
        if ($request->boolean('reset_password')) {
            $message .= ' Password telah direset ke default "password123".';
        }

        return redirect()->route('members.index')->with('success', $message);
    }

    /**
     * Hapus data anggota perpustakaan.
     */
    public function destroy(User $member)
    {
        // Proteksi: cegah pengguna yang sedang login menghapus akunnya sendiri
        if ($member->id === Auth::id()) {
            return redirect()->route('members.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Proteksi: cegah penghapusan jika anggota masih memiliki transaksi peminjaman aktif
        $activeLoansCount = $member->loans()
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->count();

        if ($activeLoansCount > 0) {
            return redirect()->route('members.index')
                ->with('error', 'Tidak dapat menghapus anggota "' . $member->name . '" karena masih memiliki ' . $activeLoansCount . ' transaksi peminjaman aktif/belum dikembalikan.');
        }

        $name = $member->name;
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota perpustakaan "' . $name . '" berhasil dihapus dari sistem.');
    }
}
