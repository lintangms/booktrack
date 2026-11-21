@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<main class="auth-wrapper">
  <aside class="auth-sidebar">
    <div class="brand-header">
      <div class="brand-logo-icon">
        <svg viewBox="0 0 24 24">
          <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
          <path d="M6 6h10"></path>
          <path d="M6 10h10"></path>
        </svg>
      </div>
      <span class="brand-name">BookTrack</span>
    </div>

    <div class="sidebar-center">
      <span class="tagline-mini">BOOKTRACK</span>
      <h2 class="quote-heading">
        “Perpustakaan adalah ruang untuk bertumbuh, satu halaman pada satu waktu.”
      </h2>
      <p class="quote-desc">
        Akses koleksi, pantau pinjaman, dan temukan bacaan berikutnya.
      </p>
    </div>
  </aside>

  <section class="auth-content">
    <div class="form-header">
      <h1 class="form-title">Selamat datang kembali</h1>
      <p class="form-subtitle">Masuk untuk mengakses layanan perpustakaan.</p>
    </div>

    @if (session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-error">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="auth-form" id="loginForm">
      @csrf
      <div class="form-group">
        <label for="email" class="form-label">
          Email <span class="required-star">*</span>
        </label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          class="form-input" 
          placeholder="nama@kampus.ac.id" 
          value="{{ old('email') }}"
          required
        >
      </div>

      <div class="form-group">
        <label for="password" class="form-label">
          Kata sandi <span class="required-star">*</span>
        </label>
        <div class="input-container has-icon-right">
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-input" 
            placeholder="Masukkan kata sandi" 
            required
          >
          <button 
            type="button" 
            class="password-toggle-btn js-toggle-password" 
            data-target="password" 
            aria-label="Lihat kata sandi"
          >
            <svg class="icon-eye" viewBox="0 0 24 24">
              <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <svg class="icon-eye-off" viewBox="0 0 24 24" style="display: none;">
              <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
              <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
              <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
              <line x1="2" y1="2" x2="22" y2="22"></line>
            </svg>
          </button>
        </div>
      </div>

      <div class="form-options">
        <label class="remember-label">
          <input type="checkbox" name="remember" class="remember-checkbox" {{ old('remember') ? 'checked' : '' }}>
          <span>Ingat saya di perangkat ini</span>
        </label>
      </div>

      <button type="submit" class="btn-submit">Masuk ke Akun</button>

      <p class="auth-redirect">
        Belum memiliki akun? <a href="{{ route('register') }}" class="link-bold">Daftar sekarang</a>
      </p>
    </form>
  </section>
</main>
@endsection
