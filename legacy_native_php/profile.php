<?php
session_start();

$currentPage = 'profil';
$pageTitle = 'Profil Saya';

// Data pengguna default sesuai tampilan referensi
$user = [
    'name' => $_SESSION['user_name'] ?? 'Alya Prameswari',
    'email' => $_SESSION['user_email'] ?? 'alya.prameswari@cendekia.ac.id',
    'role' => 'Administrator',
    'id' => 'ADM-001',
    'joined' => '10 Januari 2024'
];

// Dapatkan inisial nama untuk avatar lingkaran
$nameParts = explode(' ', trim($user['name']));
$initials = '';
foreach (array_slice($nameParts, 0, 2) as $part) {
    if (!empty($part)) {
        $initials .= mb_substr($part, 0, 1);
    }
}
$initials = strtoupper($initials) ?: 'AP';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya - BookTrack</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body class="app-body">

  <div class="app-container">
    <?php include 'includes/sidebar.php'; ?>

    <div class="app-content-wrapper">
      <?php include 'includes/header.php'; ?>

      <main class="app-page-body">
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
              <div class="profile-avatar-large"><?= htmlspecialchars($initials) ?></div>
            </div>
            <h2 class="profile-user-name"><?= htmlspecialchars($user['name']) ?></h2>
            <p class="profile-user-email"><?= htmlspecialchars($user['email']) ?></p>
            <div class="profile-badge-pill">
              <span class="badge-dot"></span>
              <span><?= htmlspecialchars($user['role']) ?></span>
            </div>
            <hr class="profile-summary-divider">
            <div class="profile-meta-list">
              <div class="profile-meta-item">
                <span class="profile-meta-label">ID Pengguna</span>
                <span class="profile-meta-value"><?= htmlspecialchars($user['id']) ?></span>
              </div>
              <div class="profile-meta-item">
                <span class="profile-meta-label">Bergabung</span>
                <span class="profile-meta-value"><?= htmlspecialchars($user['joined']) ?></span>
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

              <form id="profileForm" method="POST" action="">
                <div class="profile-form-grid">
                  <div class="profile-field-group">
                    <label for="fullName" class="profile-label">Nama Lengkap</label>
                    <input 
                      type="text" 
                      id="fullName" 
                      name="fullName" 
                      class="profile-input" 
                      value="<?= htmlspecialchars($user['name']) ?>" 
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
                      value="<?= htmlspecialchars($user['email']) ?>" 
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
                      value="<?= htmlspecialchars($user['role']) ?>" 
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
      </main>
    </div>
  </div>

  <!-- Tombol Bantuan Mengambang -->
  <button type="button" class="floating-help-btn" id="btnHelp" aria-label="Pusat Bantuan">?</button>

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
      <form id="passwordForm">
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

  <!-- Notifikasi Toast -->
  <div class="profile-toast" id="profileToast" role="alert">
    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
      <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    <span id="toastMessage">Profil berhasil diperbarui!</span>
  </div>

  <script src="assets/js/profile.js"></script>
</body>
</html>
