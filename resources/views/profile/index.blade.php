@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@endsection

@section('content')
<div class="page-header-row">
  <div>
    <h1 class="page-title">Profil Saya</h1>
    <p class="page-subtitle">Kelola informasi akun dan keamanan profil.</p>
  </div>
</div>

<div class="profile-page-grid">
  <!-- Kolom Kiri: Ringkasan Profil -->
  <div class="profile-card profile-summary-card">
    <div class="profile-avatar-wrapper">
      <div class="profile-avatar-large">{{ $user->initials }}</div>
    </div>
    <h2 class="profile-user-name">{{ $user->name }}</h2>
    <p class="profile-user-email">{{ $user->email }}</p>
    <div class="profile-badge-pill">
      <span class="badge-dot"></span>
      <span>{{ $user->role }}</span>
    </div>
    <hr class="profile-summary-divider">
    <div class="profile-meta-list">
      <div class="profile-meta-item">
        <span class="profile-meta-label">ID Pengguna</span>
        <span class="profile-meta-value">{{ $user->member_id ?? '-' }}</span>
      </div>
      <div class="profile-meta-item">
        <span class="profile-meta-label">Bergabung</span>
        <span class="profile-meta-value">{{ $user->created_at ? $user->created_at->isoFormat('D MMMM Y') : '10 Januari 2024' }}</span>
      </div>
    </div>
  </div>

  <!-- Kolom Kanan: Detail Informasi & Pengaturan Akun -->
  <div class="profile-card profile-details-card">
    <section class="profile-section">
      <div class="profile-section-header">
        <h2 class="profile-section-title">Informasi Pribadi</h2>
        <p class="profile-section-desc">Perbarui nama dan alamat email akun.</p>
      </div>

      <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="profile-form-grid">
          <div class="profile-field-group">
            <label for="fullName" class="profile-label">Nama Lengkap</label>
            <input 
              type="text" 
              id="fullName" 
              name="fullName" 
              class="profile-input" 
              value="{{ old('fullName', $user->name) }}" 
              required
            >
          </div>

          <div class="profile-field-group">
            <label for="emailAddress" class="profile-label">Email</label>
            <input 
              type="email" 
              id="emailAddress" 
              name="emailAddress" 
              class="profile-input" 
              value="{{ old('emailAddress', $user->email) }}" 
              required
            >
          </div>

          <div class="profile-field-group">
            <label for="userRole" class="profile-label">Peran</label>
            <input 
              type="text" 
              id="userRole" 
              name="userRole" 
              class="profile-input input-readonly" 
              value="{{ $user->role }}" 
              readonly
            >
          </div>

          <div class="profile-form-actions">
            <button type="submit" class="btn-save-profile">Simpan Perubahan</button>
          </div>
        </div>
      </form>
    </section>

    <section class="profile-security-section">
      <div>
        <h3 class="security-info-title">Keamanan akun</h3>
        <p class="security-info-desc">Perbarui kata sandi secara berkala.</p>
      </div>
      <button type="button" class="btn-change-password" id="btnOpenPasswordModal">Ubah Kata Sandi</button>
    </section>
  </div>
</div>

<!-- Modal Ubah Kata Sandi -->
<div class="modal-overlay" id="passwordModal" aria-hidden="true" role="dialog">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">Ubah Kata Sandi</h3>
      <button type="button" class="btn-close-modal" id="btnClosePasswordModal" aria-label="Tutup modal">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
    <form id="passwordForm" method="POST" action="{{ route('profile.password') }}">
      @csrf
      <div class="modal-body">
        <div class="profile-form-grid">
          <div class="profile-field-group">
            <label for="currentPassword" class="profile-label">Kata Sandi Saat Ini</label>
            <div class="password-input-wrapper">
              <input 
                type="password" 
                id="currentPassword" 
                name="currentPassword" 
                class="profile-input" 
                placeholder="Masukkan kata sandi saat ini" 
                required
              >
              <button 
                type="button" 
                class="password-toggle-btn js-toggle-password" 
                data-target="currentPassword" 
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

          <div class="profile-field-group">
            <label for="newPassword" class="profile-label">Kata Sandi Baru</label>
            <div class="password-input-wrapper">
              <input 
                type="password" 
                id="newPassword" 
                name="newPassword" 
                class="profile-input" 
                placeholder="Minimal 8 karakter" 
                required
              >
              <button 
                type="button" 
                class="password-toggle-btn js-toggle-password" 
                data-target="newPassword" 
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

          <div class="profile-field-group">
            <label for="confirmPassword" class="profile-label">Konfirmasi Kata Sandi Baru</label>
            <div class="password-input-wrapper">
              <input 
                type="password" 
                id="confirmPassword" 
                name="confirmPassword" 
                class="profile-input" 
                placeholder="Ulangi kata sandi baru" 
                required
              >
              <button 
                type="button" 
                class="password-toggle-btn js-toggle-password" 
                data-target="confirmPassword" 
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
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary-action" id="btnCancelPassword">Batal</button>
        <button type="submit" class="btn-save-profile">Simpan Kata Sandi</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/profile.js') }}"></script>
@endsection
