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
     * Tampilkan data pengunjung perpustakaan dengan fitur pencarian dan filter.
     */
    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat melihat dan mengelola data pengunjung.');
        }

        $query = User::query();

        // Fitur Pencarian: nama, NIM / ID pengunjung, email, dan program studi
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(member_id) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(department) LIKE ?', ["%{$search}%"]);
            });
        }

        // Filter Status Pengunjung
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Urutkan pengunjung dari yang terbaru
        $members = $query->orderBy('id', 'desc')->get();

        // Metrik Ringkasan
        $totalMembers = User::count();
        $activeMembers = User::where('status', 'Aktif')->count();
        $totalStudents = User::where(function ($q) {
            $q->whereIn('role', ['Pengunjung', 'Anggota'])
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
     * Simpan data pengunjung perpustakaan baru.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat melihat dan mengelola data pengunjung.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'string', 'max:50', 'unique:users,member_id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string', Rule::in(['Aktif', 'Nonaktif'])],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'member_id.required' => 'NIM / Nomor Induk Pengunjung wajib diisi.',
            'member_id.unique' => 'NIM / Nomor Induk Pengunjung ini sudah terdaftar.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'status.required' => 'Status pengunjung wajib dipilih.',
            'status.in' => 'Status pengunjung yang dipilih tidak valid.',
        ]);

        $validated['role'] = 'Pengunjung';
        $validated['password'] = Hash::make('password123');

        $user = User::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Pengunjung perpustakaan "' . $user->name . '" berhasil didaftarkan dengan password default "password123".');
    }

    /**
     * Perbarui data pengunjung perpustakaan.
     */
    public function update(Request $request, User $member)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat melihat dan mengelola data pengunjung.');
        }

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
            'member_id.required' => 'NIM / Nomor Induk Pengunjung wajib diisi.',
            'member_id.unique' => 'NIM / Nomor Induk Pengunjung sudah digunakan pengguna lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'status.required' => 'Status pengunjung wajib dipilih.',
            'status.in' => 'Status pengunjung yang dipilih tidak valid.',
        ]);

        if ($request->boolean('reset_password')) {
            $validated['password'] = Hash::make('password123');
        }

        unset($validated['reset_password']);

        $member->update($validated);

        $message = 'Data pengunjung "' . $member->name . '" berhasil diperbarui.';
        if ($request->boolean('reset_password')) {
            $message .= ' Password telah direset ke default "password123".';
        }

        return redirect()->route('members.index')->with('success', $message);
    }

    /**
     * Hapus data pengunjung perpustakaan.
     */
    public function destroy(User $member)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat melihat dan mengelola data pengunjung.');
        }

        // Proteksi: cegah pengguna yang sedang login menghapus akunnya sendiri
        if ($member->id === Auth::id()) {
            return redirect()->route('members.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Proteksi: cegah penghapusan jika pengunjung masih memiliki transaksi peminjaman aktif
        $activeLoansCount = $member->loans()
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->count();

        if ($activeLoansCount > 0) {
            return redirect()->route('members.index')
                ->with('error', 'Tidak dapat menghapus pengunjung "' . $member->name . '" karena masih memiliki ' . $activeLoansCount . ' transaksi peminjaman aktif/belum dikembalikan.');
        }

        $name = $member->name;
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Pengunjung perpustakaan "' . $name . '" berhasil dihapus dari sistem.');
    }
}
