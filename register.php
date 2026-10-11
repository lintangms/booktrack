<?php
require_once __DIR__ . '/includes/auth.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $member_id = trim($_POST['member_id'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    if (empty($fullname) || empty($member_id) || empty($email) || empty($password)) {
        $message = 'Harap isi semua kolom yang bertanda bintang (*).';
        $message_type = 'error';
    } elseif ($password !== $password_confirmation) {
        $message = 'Konfirmasi kata sandi tidak cocok.';
        $message_type = 'error';
    } elseif (strlen($password) < 8) {
        $message = 'Kata sandi minimal harus 8 karakter.';
        $message_type = 'error';
    } else {
        $result = registerUser($fullname, $member_id, $email, $password);
        if ($result['success']) {
            $_SESSION['flash_message'] = 'Pendaftaran berhasil! Selamat datang, ' . $fullname . '.';
            header('Location: dashboard.php');
            exit;
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun - BookTrack</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-body">

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
        <h1 class="form-title">Buat akun BookTrack</h1>
        <p class="form-subtitle">Daftarkan diri untuk mulai menggunakan layanan perpustakaan.</p>
      </div>

      <?php if (!empty($message)): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?>">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <form action="register.php" method="POST" class="auth-form" id="registerForm">
        <div class="form-group">
          <label for="fullname" class="form-label">
            Nama lengkap <span class="required-star">*</span>
          </label>
          <input 
            type="text" 
            id="fullname" 
            name="fullname" 
            class="form-input" 
            placeholder="Masukkan nama lengkap" 
            value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
            required
          >
        </div>

        <div class="form-group">
          <label for="member_id" class="form-label">
            Nomor identitas anggota <span class="required-star">*</span>
          </label>
          <input 
            type="text" 
            id="member_id" 
            name="member_id" 
            class="form-input" 
            placeholder="Contoh: 231104028" 
            value="<?= htmlspecialchars($_POST['member_id'] ?? '') ?>"
            required
          >
        </div>

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
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
              placeholder="Minimal 8 karakter" 
              required
            >
            <button 
              type="button" 
              class="input-action-btn js-toggle-password" 
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

        <div class="form-group">
          <label for="password_confirmation" class="form-label">
            Konfirmasi kata sandi <span class="required-star">*</span>
          </label>
          <div class="input-container has-icon-right">
            <input 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              class="form-input" 
              placeholder="Ulangi kata sandi" 
              required
            >
            <button 
              type="button" 
              class="input-action-btn js-toggle-password" 
              data-target="password_confirmation" 
              aria-label="Lihat konfirmasi kata sandi"
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

        <button type="submit" class="btn-submit">
          Buat Akun
        </button>
      </form>

      <div class="form-footer">
        Sudah punya akun? <a href="login.php">Masuk</a>
      </div>
    </section>
  </main>

  <script src="assets/js/script.js"></script>
</body>
</html>
