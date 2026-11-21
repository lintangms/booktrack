document.addEventListener('DOMContentLoaded', () => {
  const sidebarToggle = document.getElementById('sidebarToggle');
  const appSidebar = document.getElementById('appSidebar');

  if (sidebarToggle && appSidebar) {
    sidebarToggle.addEventListener('click', () => {
      appSidebar.classList.toggle('show-sidebar');
    });

    document.addEventListener('click', (e) => {
      if (
        window.innerWidth <= 768 &&
        appSidebar.classList.contains('show-sidebar') &&
        !appSidebar.contains(e.target) &&
        !sidebarToggle.contains(e.target)
      ) {
        appSidebar.classList.remove('show-sidebar');
      }
    });
  }

  // User Profile Dropdown Toggle
  const userProfileToggle = document.getElementById('userProfileToggle');
  const userDropdownMenu = document.getElementById('userDropdownMenu');

  if (userProfileToggle && userDropdownMenu) {
    userProfileToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const isExpanded = userDropdownMenu.classList.toggle('show');
      userProfileToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    });

    document.addEventListener('click', (e) => {
      if (!userDropdownMenu.contains(e.target) && !userProfileToggle.contains(e.target)) {
        userDropdownMenu.classList.remove('show');
        userProfileToggle.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && userDropdownMenu.classList.contains('show')) {
        userDropdownMenu.classList.remove('show');
        userProfileToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  const searchInput = document.getElementById('searchBookInput');
  const categoryFilter = document.getElementById('categoryFilter');
  const btnResetFilter = document.getElementById('btnResetFilter');
  const bookRows = document.querySelectorAll('.book-row');

  function applyFilters() {
    if (!bookRows.length) return;

    const searchTerm = (searchInput ? searchInput.value : '').toLowerCase().trim();
    const selectedCategory = categoryFilter ? categoryFilter.value : 'Semua';

    bookRows.forEach((row) => {
      const title = row.getAttribute('data-title') || '';
      const author = row.getAttribute('data-author') || '';
      const isbn = row.getAttribute('data-isbn') || '';
      const category = row.getAttribute('data-category') || '';

      const matchesSearch =
        !searchTerm ||
        title.includes(searchTerm) ||
        author.includes(searchTerm) ||
        isbn.includes(searchTerm);

      const matchesCategory =
        selectedCategory === 'Semua' || category === selectedCategory;

      if (matchesSearch && matchesCategory) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
  }

  if (categoryFilter) {
    categoryFilter.addEventListener('change', applyFilters);
  }

  if (btnResetFilter) {
    btnResetFilter.addEventListener('click', () => {
      if (searchInput) searchInput.value = '';
      if (categoryFilter) categoryFilter.value = 'Semua';
      applyFilters();
    });
  }

  const btnAddBook = document.getElementById('btnAddBook');
  if (btnAddBook) {
    btnAddBook.addEventListener('click', () => {
      alert('Fitur Tambah Buku: Formulir tambah koleksi buku perpustakaan.');
    });
  }
});
