<aside class="app-sidebar" id="appSidebar">
  <div class="sidebar-brand">
    <div class="brand-text">Library Management</div>
  </div>

  <div class="sidebar-nav-group">
    <span class="nav-section-title">MENU UTAMA</span>
    <ul class="nav-list">
      <li class="nav-item">
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard', 'user.dashboard', 'dashboard') ? 'active' : '' }}">
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
        <a href="{{ Auth::user()->isAdmin() ? route('admin.books.index') : route('user.books.index') }}" class="nav-link {{ request()->routeIs('*books.*') ? 'active' : '' }}">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
            <path d="M6 6h10"></path>
            <path d="M6 10h10"></path>
          </svg>
          <span class="nav-label">{{ Auth::user()->isAdmin() ? 'Data Buku' : 'Katalog Buku' }}</span>
        </a>
      </li>
      @if(Auth::check() && Auth::user()->isAdmin())
      <li class="nav-item">
        <a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('*members.*') ? 'active' : '' }}">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          <span class="nav-label">Data Pengunjung</span>
        </a>
      </li>
      @endif
      <li class="nav-item">
        <a href="{{ Auth::user()->isAdmin() ? route('admin.loans.index') : route('user.loans.index') }}" class="nav-link {{ request()->routeIs('*loans.*') ? 'active' : '' }}">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M7 10h14l-4-4"></path>
            <path d="M17 14H3l4 4"></path>
          </svg>
          <span class="nav-label">{{ Auth::user()->isAdmin() ? 'Peminjaman' : 'Peminjaman Saya' }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ Auth::user()->isAdmin() ? route('admin.returns.index') : route('user.returns.index') }}" class="nav-link {{ request()->routeIs('*returns.*') ? 'active' : '' }}">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
            <path d="M3 3v5h5"></path>
          </svg>
          <span class="nav-label">{{ Auth::user()->isAdmin() ? 'Pengembalian' : 'Pengembalian Saya' }}</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="sidebar-nav-group sidebar-nav-bottom">
    <span class="nav-section-title">AKUN</span>
    <ul class="nav-list">
      <li class="nav-item">
        <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
          <span class="nav-indicator"></span>
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span class="nav-label">Profil Saya</span>
        </a>
      </li>
      <li class="nav-item">
        <form id="logoutFormSidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormSidebar').submit();" class="nav-link logout-link">
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
