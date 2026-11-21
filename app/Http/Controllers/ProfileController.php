<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fullName' => ['required', 'string', 'max:255'],
            'emailAddress' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ], [
            'fullName.required' => 'Nama lengkap wajib diisi.',
            'emailAddress.required' => 'Email wajib diisi.',
            'emailAddress.email' => 'Format email tidak valid.',
            'emailAddress.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->name = $validated['fullName'];
        $user->email = $validated['emailAddress'];
        $user->save();

        return back()->with('success', 'Profil berhasil disimpan!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'string', 'min:8', 'different:currentPassword'],
            'confirmPassword' => ['required', 'string', 'same:newPassword'],
        ], [
            'currentPassword.required' => 'Kata sandi saat ini wajib diisi.',
            'newPassword.required' => 'Kata sandi baru wajib diisi.',
            'newPassword.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'newPassword.different' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.',
            'confirmPassword.required' => 'Konfirmasi kata sandi baru wajib diisi.',
            'confirmPassword.same' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return back()->withErrors(['currentPassword' => 'Kata sandi saat ini tidak cocok.']);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui!');
    }
}
