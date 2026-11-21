@extends('layouts.auth')

@section('title', 'Selamat Datang')

@section('content')
<main class="landing-hero">
  <div style="display: flex; justify-content: center; margin-bottom: 20px;">
    <div class="brand-logo-icon" style="background-color: var(--primary-green); width: 56px; height: 56px; border-radius: 12px; border: none;">
      <svg viewBox="0 0 24 24" style="width: 32px; height: 32px; stroke: #ffffff;">
        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
        <path d="M6 6h10"></path>
        <path d="M6 10h10"></path>
      </svg>
    </div>
  </div>

  <h1>BookTrack</h1>
  <p>
    “Perpustakaan adalah ruang untuk bertumbuh, satu halaman pada satu waktu.”<br>
    Akses ribuan koleksi buku digital, pantau status pinjaman Anda, dan temukan rekomendasi bacaan berikutnya dengan mudah.
  </p>

  <div class="landing-actions">
    <a href="{{ route('login') }}" class="btn-primary-link">Masuk ke Akun</a>
    <a href="{{ route('register') }}" class="btn-secondary">Daftar Akun Baru</a>
  </div>
</main>
@endsection
