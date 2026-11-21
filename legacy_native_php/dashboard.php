<?php
require_once __DIR__ . '/includes/auth.php';
$currentPage = 'dashboard';
$pageTitle = 'Dashboard';
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - BookTrack</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="app-body">

  <div class="app-container">
    <?php include 'includes/sidebar.php'; ?>

    <div class="app-content-wrapper">
      <?php include 'includes/header.php'; ?>

      <main class="app-page-body">
        <div class="page-header-row">
          <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Selamat datang kembali, <strong><?= htmlspecialchars($currentUser['fullname']) ?></strong>. Ringkasan aktivitas dan koleksi perpustakaan.</p>
          </div>
        </div>

        <section class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
                <path d="M6 6h10"></path>
                <path d="M6 10h10"></path>
              </svg>
            </div>
            <div class="stat-info">
              <span class="stat-label">Total Buku</span>
              <span class="stat-value">52</span>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
            </div>
            <div class="stat-info">
              <span class="stat-label">Total Anggota</span>
              <span class="stat-value">5</span>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-orange">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 10h14l-4-4"></path>
                <path d="M17 14H3l4 4"></path>
              </svg>
            </div>
            <div class="stat-info">
              <span class="stat-label">Sedang Dipinjam</span>
              <span class="stat-value">4</span>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-red">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <div class="stat-info">
              <span class="stat-label">Buku Terlambat</span>
              <span class="stat-value">1</span>
            </div>
          </div>
        </section>

        <section class="dashboard-chart-card">
          <div class="chart-card-header">
            <div>
              <h2 class="chart-card-title">Aktivitas Peminjaman</h2>
              <p class="chart-card-subtitle">Enam bulan terakhir</p>
            </div>
            <div>
              <select class="select-pill" aria-label="Tahun">
                <option value="2025" selected>2025</option>
                <option value="2024">2024</option>
              </select>
            </div>
          </div>

          <div class="chart-bars-container">
            <div class="chart-bar-group">
              <div class="chart-bar-pill" style="height: 45%;"></div>
              <span class="chart-bar-label">Jan</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar-pill" style="height: 70%;"></div>
              <span class="chart-bar-label">Feb</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar-pill" style="height: 55%;"></div>
              <span class="chart-bar-label">Mar</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar-pill" style="height: 85%;"></div>
              <span class="chart-bar-label">Apr</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar-pill" style="height: 60%;"></div>
              <span class="chart-bar-label">Mei</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar-pill highlight" style="height: 95%;"></div>
              <span class="chart-bar-label">Jun</span>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <script src="assets/js/dashboard.js"></script>
</body>
</html>
