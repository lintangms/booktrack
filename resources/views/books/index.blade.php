@extends('layouts.app')

@section('title', 'Data Buku')
@section('page_title', 'Data Buku')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/data-buku.css') }}">
  <style>
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background-color: rgba(15, 23, 42, 0.55);
      z-index: 1000;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal-overlay.active {
      display: flex;
    }
    .modal-box {
      background: #ffffff;
      border-radius: 16px;
      width: 100%;
      max-width: 520px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      animation: modalFadeIn 0.2s ease-out;
    }
    @keyframes modalFadeIn {
      from { opacity: 0; transform: scale(0.96); }
      to { opacity: 1; transform: scale(1); }
    }
    .modal-header-custom {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 24px;
      border-bottom: 1px solid #e2e8f0;
    }
    .modal-header-custom h3 {
      font-size: 18px;
      font-weight: 700;
      color: #0f172a;
      margin: 0;
    }
    .btn-close-custom {
      background: none;
      border: none;
      color: #64748b;
      cursor: pointer;
      font-size: 20px;
      padding: 4px;
      line-height: 1;
    }
    .btn-close-custom:hover { color: #0f172a; }
    .modal-body-custom {
      padding: 20px 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .form-field-custom {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .form-field-custom label {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
    }
    .form-field-custom input, .form-field-custom select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: #1e293b;
      box-sizing: border-box;
    }
    .form-field-custom input:focus, .form-field-custom select:focus {
      outline: none;
      border-color: #059669;
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .modal-footer-custom {
      padding: 16px 24px;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      display: flex;
      justify-content: flex-end;
      gap: 12px;
    }
    .btn-cancel-custom {
      padding: 10px 16px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #475569;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-submit-custom {
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      background: #059669;
      color: #ffffff;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-submit-custom:hover { background: #047857; }
  </style>
@endsection

@section('content')
<div class="page-header-row">
  <div>
    <h1 class="page-title">Data Buku</h1>
    <p class="page-subtitle">Kelola koleksi buku yang tersedia di perpustakaan.</p>
  </div>
  @if(Auth::check() && Auth::user()->isAdmin())
  <div>
    <button type="button" class="btn-primary-action" id="openAddBookModal">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>Tambah Buku</span>
    </button>
  </div>
  @endif
</div>

<section class="books-summary-card">
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Judul</span>
    <span class="summary-stat-number">{{ $totalTitles }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Eksemplar</span>
    <span class="summary-stat-number">{{ $totalCopies }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Buku Tersedia</span>
    <span class="summary-stat-number">{{ $availableCopies }}</span>
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
    @foreach ($categories as $cat)
      <option value="{{ $cat }}">{{ $cat }}</option>
    @endforeach
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
      @forelse ($books as $book)
        <tr class="book-row" 
            data-title="{{ strtolower($book->title) }}"
            data-author="{{ strtolower($book->author) }}"
            data-isbn="{{ strtolower($book->isbn) }}"
            data-category="{{ $book->category }}">
          <td>
            <div class="book-info-col">
              <div class="book-cover-badge {{ $book->theme }}">
                <span class="badge-abbr">{{ $book->code }}</span>
                <span class="badge-mini-title">{{ $book->title }}</span>
              </div>
              <div class="book-meta">
                <span class="book-title">{{ $book->title }}</span>
                <span class="book-author">{{ $book->author }}</span>
              </div>
            </div>
          </td>
          <td>
            <span class="isbn-text">{{ $book->isbn }}</span>
          </td>
          <td>
            <span class="category-badge">{{ $book->category }}</span>
          </td>
          <td>
            <span class="stock-num">{{ $book->stock }}</span>
          </td>
          <td>
            <span class="stock-num">{{ $book->available }}</span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align: center; padding: 32px; color: #64748b;">
            Belum ada koleksi buku yang terdaftar.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</section>

@if(Auth::check() && Auth::user()->isAdmin())
<!-- Modal Tambah Buku -->
<div class="modal-overlay" id="addBookModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Tambah Koleksi Buku Baru</h3>
      <button type="button" class="btn-close-custom" id="closeAddBookModal">&times;</button>
    </div>
    <form action="{{ route('books.store') }}" method="POST">
      @csrf
      <div class="modal-body-custom">
        <div class="form-field-custom">
          <label for="bookTitle">Judul Buku *</label>
          <input type="text" id="bookTitle" name="title" placeholder="Contoh: Laskar Pelangi" required>
        </div>
        <div class="form-field-custom">
          <label for="bookAuthor">Penulis *</label>
          <input type="text" id="bookAuthor" name="author" placeholder="Contoh: Andrea Hirata" required>
        </div>
        <div class="form-field-custom">
          <label for="bookIsbn">Nomor ISBN *</label>
          <input type="text" id="bookIsbn" name="isbn" placeholder="Contoh: 978-979-3062-79-2" required>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-field-custom">
            <label for="bookCategory">Kategori *</label>
            <input type="text" id="bookCategory" name="category" placeholder="Fiksi, Sejarah, dsb" required>
          </div>
          <div class="form-field-custom">
            <label for="bookTheme">Tema Sampul</label>
            <select id="bookTheme" name="theme">
              <option value="cover-teal">Teal (Hijau)</option>
              <option value="cover-amber">Amber (Oranye)</option>
              <option value="cover-blue">Blue (Biru)</option>
              <option value="cover-emerald">Emerald (Zamrud)</option>
            </select>
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-field-custom">
            <label for="bookStock">Total Stok *</label>
            <input type="number" id="bookStock" name="stock" min="1" value="10" required>
          </div>
          <div class="form-field-custom">
            <label for="bookAvailable">Stok Tersedia</label>
            <input type="number" id="bookAvailable" name="available" min="0" value="10">
          </div>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelAddBookModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Simpan Buku</button>
      </div>
    </form>
  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('addBookModal');
    const openBtn = document.getElementById('openAddBookModal');
    const closeBtn = document.getElementById('closeAddBookModal');
    const cancelBtn = document.getElementById('cancelAddBookModal');

    if (openBtn && modal) {
      openBtn.addEventListener('click', () => modal.classList.add('active'));
    }
    if (closeBtn && modal) {
      closeBtn.addEventListener('click', () => modal.classList.remove('active'));
    }
    if (cancelBtn && modal) {
      cancelBtn.addEventListener('click', () => modal.classList.remove('active'));
    }
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.remove('active');
      });
    }
  });
</script>
@endsection
