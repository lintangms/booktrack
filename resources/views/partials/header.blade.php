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
      <span class="breadcrumb-current">@yield('page_title', 'Dashboard')</span>
    </nav>
  </div>

  <div class="topbar-right">
    <div class="user-profile-widget" id="userProfileToggle" style="cursor: pointer; position: relative;">
      <div class="user-avatar-circle">{{ Auth::user() ? Auth::user()->initials : 'AP' }}</div>
      <div class="user-details">
        <span class="user-name">{{ Auth::user() ? Auth::user()->name : 'Pengguna' }}</span>
        <span class="user-role">{{ Auth::user() ? Auth::user()->role : 'Anggota' }}</span>
      </div>
      <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    </div>

    <!-- Dropdown Menu -->
    <div id="userDropdownMenu" class="user-dropdown-menu" style="display: none; position: absolute; right: 24px; top: 70px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; width: 190px; z-index: 100; overflow: hidden;">
      <a href="{{ route('profile') }}" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #334155; text-decoration: none; font-size: 14px; font-weight: 500;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span>Profil Saya</span>
      </a>
      <div style="height: 1px; background: #f1f5f9;"></div>
      <form action="{{ route('logout') }}" method="POST" id="topbarLogoutForm">
        @csrf
        <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #ef4444; background: none; border: none; font-size: 14px; font-weight: 500; cursor: pointer; text-align: left;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          <span>Keluar</span>
        </button>
      </form>
    </div>
  </div>
</header>
