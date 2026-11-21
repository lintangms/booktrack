<?php
$currentPage = 'data-buku';
$pageTitle = 'Data Buku';

$books = [
    [
        'code' => 'LP',
        'theme' => 'cover-teal',
        'title' => 'Laskar Pelangi',
        'author' => 'Andrea Hirata',
        'isbn' => '978-979-3062-79-2',
        'category' => 'Fiksi',
        'stock' => 12,
        'available' => 8
    ],
    [
        'code' => 'BM',
        'theme' => 'cover-amber',
        'title' => 'Bumi Manusia',
        'author' => 'Pramoedya Ananta Toer',
        'isbn' => '978-979-97312-3-4',
        'category' => 'Sejarah',
        'stock' => 8,
        'available' => 3
    ],
    [
        'code' => 'FT',
        'theme' => 'cover-blue',
        'title' => 'Filosofi Teras',
        'author' => 'Henry Manampiring',
        'isbn' => '978-602-412-518-9',
        'category' => 'Pengembangan Diri',
        'stock' => 10,
        'available' => 5
    ],
    [
        'code' => 'HJ',
        'theme' => 'cover-emerald',
        'title' => 'Hujan',
        'author' => 'Tere Liye',
        'isbn' => '978-602-03-2478-4',
        'category' => 'Fiksi',
        'stock' => 9,
        'available' => 4
    ],
    [
        'code' => 'LB',
        'theme' => 'cover-teal',
        'title' => 'Laut Bercerita',
        'author' => 'Leila S. Chudori',
        'isbn' => '978-602-424-694-5',
        'category' => 'Fiksi',
        'stock' => 7,
        'available' => 2
    ],
    [
        'code' => 'AH',
        'theme' => 'cover-blue',
        'title' => 'Atomic Habits',
        'author' => 'James Clear',
        'isbn' => '978-602-06-3317-6',
        'category' => 'Pengembangan Diri',
        'stock' => 6,
        'available' => 2
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Buku - BookTrack</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/data-buku.css">
</head>
<body class="app-body">

  <div class="app-container">
    <?php include 'includes/sidebar.php'; ?>

    <div class="app-content-wrapper">
      <?php include 'includes/header.php'; ?>

      <main class="app-page-body">
        <div class="page-header-row">
          <div>
            <h1 class="page-title">Data Buku</h1>
            <p class="page-subtitle">Kelola koleksi buku yang tersedia di perpustakaan.</p>
          </div>
          <div>
            <button type="button" class="btn-primary-action" id="btnAddBook">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              <span>Tambah Buku</span>
            </button>
          </div>
        </div>

        <section class="books-summary-card">
          <div class="summary-stat-item">
            <span class="summary-stat-label">Total Judul</span>
            <span class="summary-stat-number">6</span>
          </div>
          <div class="summary-stat-item">
            <span class="summary-stat-label">Total Eksemplar</span>
            <span class="summary-stat-number">52</span>
          </div>
          <div class="summary-stat-item">
            <span class="summary-stat-label">Buku Tersedia</span>
            <span class="summary-stat-number">24</span>
          </div>
        </section>

        <section class="table-toolbar">
          <div class="search-box-wrapper">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input 
              type="text" 
              id="searchBookInput" 
              class="search-input" 
              placeholder="Cari judul, penulis, atau ISBN"
              autocomplete="off"
            >
          </div>

          <select class="filter-select" id="categoryFilter" aria-label="Filter Kategori">
            <option value="Semua">Semua</option>
            <option value="Fiksi">Fiksi</option>
            <option value="Sejarah">Sejarah</option>
            <option value="Pengembangan Diri">Pengembangan Diri</option>
          </select>

          <button type="button" class="btn-reset-filter" id="btnResetFilter">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
              <path d="M3 3v5h5"></path>
            </svg>
            <span>Reset</span>
          </button>
        </section>

        <section class="table-container">
          <table class="data-table" id="booksTable">
            <thead>
              <tr>
                <th style="width: 38%;">BUKU</th>
                <th style="width: 24%;">ISBN</th>
                <th style="width: 18%;">KATEGORI</th>
                <th style="width: 10%;">STOK</th>
                <th style="width: 10%;">TERSEDIA</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($books as $book): ?>
                <tr class="book-row" 
                    data-title="<?= strtolower(htmlspecialchars($book['title'])) ?>"
                    data-author="<?= strtolower(htmlspecialchars($book['author'])) ?>"
                    data-isbn="<?= strtolower(htmlspecialchars($book['isbn'])) ?>"
                    data-category="<?= htmlspecialchars($book['category']) ?>">
                  <td>
                    <div class="book-info-col">
                      <div class="book-cover-badge <?= $book['theme'] ?>">
                        <span class="badge-abbr"><?= htmlspecialchars($book['code']) ?></span>
                        <span class="badge-mini-title"><?= htmlspecialchars($book['title']) ?></span>
                      </div>
                      <div class="book-meta">
                        <span class="book-title"><?= htmlspecialchars($book['title']) ?></span>
                        <span class="book-author"><?= htmlspecialchars($book['author']) ?></span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="isbn-text"><?= htmlspecialchars($book['isbn']) ?></span>
                  </td>
                  <td>
                    <span class="category-badge"><?= htmlspecialchars($book['category']) ?></span>
                  </td>
                  <td>
                    <span class="stock-num"><?= htmlspecialchars($book['stock']) ?></span>
                  </td>
                  <td>
                    <span class="stock-num"><?= htmlspecialchars($book['available']) ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </section>
      </main>
    </div>
  </div>

  <script src="assets/js/dashboard.js"></script>
</body>
</html>
