<header class="app-topbar">
  <div class="topbar-left">
    <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
      </svg>
    </button>

    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <span class="breadcrumb-parent">BookTrack</span>
      <span class="breadcrumb-separator">&rsaquo;</span>
      <span class="breadcrumb-current"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></span>
    </nav>
  </div>

  <div class="topbar-right">
    <div class="user-profile-widget">
      <div class="user-avatar-circle">AP</div>
      <div class="user-details">
        <span class="user-name">Alya Prameswari</span>
        <span class="user-role">Administrator</span>
      </div>
      <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    </div>
  </div>
</header>
