<?php
function isActive($pageName, $target) {
    return $pageName === $target ? 'active' : '';
}
?>
<aside class="app-sidebar" id="appSidebar">
  <div class="sidebar-brand">
    <div class="brand-text">Library Management</div>
  </div>

  <div class="sidebar-nav-group">
    <span class="nav-section-title">MENU UTAMA</span>
    <ul class="nav-list">
      <li class="nav-item">
        <a href="dashboard.php" class="nav-link <?= isActive($currentPage ?? '', 'dashboard') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
          </svg>
          <span class="nav-label">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="data-buku.php" class="nav-link <?= isActive($currentPage ?? '', 'data-buku') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
            <path d="M6 6h10"></path>
            <path d="M6 10h10"></path>
          </svg>
          <span class="nav-label">Data Buku</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link <?= isActive($currentPage ?? '', 'peminjaman') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M7 10h14l-4-4"></path>
            <path d="M17 14H3l4 4"></path>
          </svg>
          <span class="nav-label">Peminjaman</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link <?= isActive($currentPage ?? '', 'pengembalian') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
            <path d="M3 3v5h5"></path>
          </svg>
          <span class="nav-label">Pengembalian</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link <?= isActive($currentPage ?? '', 'status-buku') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 20V10"></path>
            <path d="M12 20V4"></path>
            <path d="M6 20v-6"></path>
          </svg>
          <span class="nav-label">Status Buku</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link <?= isActive($currentPage ?? '', 'cari-buku') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <span class="nav-label">Cari Buku</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="sidebar-nav-group sidebar-nav-bottom">
    <span class="nav-section-title">AKUN</span>
    <ul class="nav-list">
      <li class="nav-item">
        <a href="#" class="nav-link <?= isActive($currentPage ?? '', 'profil') ?>">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span class="nav-label">Profil Saya</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="login.php" class="nav-link logout-link">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          <span class="nav-label">Keluar</span>
        </a>
      </li>
    </ul>
  </div>
</aside>
