@extends('layouts.app')

@section('title', 'Transaksi Peminjaman')
@section('page_title', 'Transaksi Peminjaman')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/data-buku.css') }}">
  <style>
    .loan-id-text {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-size: 0.85rem;
      font-weight: 700;
      color: #0f172a;
      background: #f1f5f9;
      padding: 4px 8px;
      border-radius: 6px;
      display: inline-block;
    }

    .badge-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 0.775rem;
      font-weight: 600;
      line-height: 1.2;
    }
    .badge-status-pill.status-dipinjam {
      background-color: #eff6ff;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
    }
    .badge-status-pill.status-terlambat {
      background-color: #fef2f2;
      color: #b91c1c;
      border: 1px solid #fecaca;
    }
    .badge-status-pill.status-dikembalikan {
      background-color: #ecfdf5;
      color: #047857;
      border: 1px solid #a7f3d0;
    }
    .status-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background-color: currentColor;
    }

    .due-date-text {
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text-body);
    }
    .due-date-overdue {
      color: #dc2626 !important;
      font-weight: 700 !important;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .action-btn-group {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .btn-table-action {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.775rem;
      font-weight: 600;
      cursor: pointer;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #334155;
      transition: all 0.15s ease;
    }
    .btn-table-action:hover {
      background: #f8fafc;
      color: #0f172a;
      border-color: #94a3b8;
    }
    .btn-table-action.btn-delete:hover {
      background: #fef2f2;
      color: #b91c1c;
      border-color: #fca5a5;
    }

    /* Modal Styles */
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
      max-width: 540px;
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
      max-height: 75vh;
      overflow-y: auto;
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
    .form-field-custom input, 
    .form-field-custom select, 
    .form-field-custom textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: #1e293b;
      box-sizing: border-box;
      background: #ffffff;
    }
    .form-field-custom input:focus, 
    .form-field-custom select:focus, 
    .form-field-custom textarea:focus {
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

    .modal-info-box {
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 12px 14px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      font-size: 13px;
    }
    .modal-info-row {
      display: flex;
      justify-content: space-between;
    }
    .modal-info-label {
      color: #64748b;
      font-weight: 500;
    }
    .modal-info-val {
      color: #0f172a;
      font-weight: 600;
    }
  </style>
@endsection

@section('content')
<div class="page-header-row">
  <div>
    <h1 class="page-title">Transaksi Peminjaman</h1>
    <p class="page-subtitle">Kelola riwayat dan transaksi peminjaman buku perpustakaan.</p>
  </div>
  @if(Auth::check() && Auth::user()->isAdmin())
  <div>
    <button type="button" class="btn-primary-action" id="openAddLoanModal">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>Catat Peminjaman</span>
    </button>
  </div>
  @endif
</div>

<!-- Card Ringkasan Statistik -->
<section class="books-summary-card">
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Transaksi</span>
    <span class="summary-stat-number">{{ $totalLoans }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Sedang Dipinjam</span>
    <span class="summary-stat-number" style="color: #2563eb;">{{ $borrowedCount }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Buku Terlambat</span>
    <span class="summary-stat-number" style="color: #dc2626;">{{ $overdueCount }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Selesai Dikembalikan</span>
    <span class="summary-stat-number" style="color: #059669;">{{ $returnedCount }}</span>
  </div>
</section>

<!-- Toolbar Pencarian & Filter -->
<section class="table-toolbar">
  <div class="search-box-wrapper">
    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="11" cy="11" r="8"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
    <input 
      type="text" 
      id="searchLoanInput" 
      class="search-input" 
      placeholder="Cari nama peminjam atau judul buku..."
      autocomplete="off"
      value="{{ request('search') }}"
    >
  </div>

  <select class="filter-select" id="statusFilter" aria-label="Filter Status">
    <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
    <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
  </select>

  <button type="button" class="btn-reset-filter" id="btnResetFilter">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
      <path d="M3 3v5h5"></path>
    </svg>
    <span>Reset</span>
  </button>
</section>

<!-- Tabel Data Peminjaman -->
<section class="table-container">
  <table class="data-table" id="loansTable">
    <thead>
      <tr>
        <th style="width: 8%; text-align: center;">#</th>
        <th style="width: 22%;">PEMINJAM</th>
        <th style="width: 26%;">BUKU</th>
        <th style="width: 13%;">TGL PINJAM</th>
        <th style="width: 13%;">JATUH TEMPO</th>
        <th style="width: 10%;">STATUS</th>
        @if(Auth::check() && Auth::user()->isAdmin())
          <th style="width: 14%; text-align: center;">AKSI</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @forelse ($loans as $index => $loan)
        @php
          $isOverdue = in_array($loan->status, ['Terlambat', 'overdue']) || 
                       ($loan->status === 'Dipinjam' && \Carbon\Carbon::parse($loan->due_date)->isPast() && empty($loan->return_date));
          $displayStatus = in_array($loan->status, ['borrowed', 'Dipinjam']) ? 'Dipinjam' : 
                           (in_array($loan->status, ['overdue', 'Terlambat']) ? 'Terlambat' : 'Dikembalikan');
          $statusClass = strtolower($displayStatus);
        @endphp
        <tr class="loan-row" 
            data-user="{{ strtolower($loan->user->name ?? '') }}"
            data-book="{{ strtolower($loan->book->title ?? '') }}"
            data-status="{{ $displayStatus }}">
          <td style="text-align: center;">
            <span class="loan-id-text">#{{ $loan->id }}</span>
          </td>
          <td>
            <div style="display: flex; flex-direction: column;">
              <span style="font-weight: 600; color: #1e293b;">{{ $loan->user->name ?? 'User Tidak Diketahui' }}</span>
              <span style="font-size: 0.775rem; color: #64748b;">
                {{ $loan->user->member_id ?? $loan->user->email ?? 'Pengunjung' }}
              </span>
            </div>
          </td>
          <td>
            <div class="book-info-col">
              @if ($loan->book)
                <div class="book-cover-badge {{ $loan->book->theme ?? 'cover-teal' }}" style="height: 48px; width: 36px;">
                  <span class="badge-abbr" style="font-size: 0.65rem;">{{ $loan->book->code }}</span>
                </div>
                <div class="book-meta">
                  <span class="book-title" style="font-size: 0.85rem;">{{ $loan->book->title }}</span>
                  <span class="book-author" style="font-size: 0.75rem;">{{ $loan->book->author }}</span>
                </div>
              @else
                <span style="color: #94a3b8; font-style: italic;">Buku Dihapus</span>
              @endif
            </div>
          </td>
          <td>
            <span style="font-size: 0.85rem; color: #475569;">
              {{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d M Y') }}
            </span>
          </td>
          <td>
            @if ($isOverdue && $displayStatus !== 'Dikembalikan')
              <span class="due-date-text due-date-overdue" title="Melewati batas pengembalian!">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d M Y') }}</span>
              </span>
            @else
              <span class="due-date-text">
                {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d M Y') }}
              </span>
            @endif
          </td>
          <td>
            <span class="badge-status-pill status-{{ $statusClass }}">
              <span class="status-dot"></span>
              <span>{{ $displayStatus }}</span>
            </span>
          </td>
          @if(Auth::check() && Auth::user()->isAdmin())
          <td>
            <div class="action-btn-group" style="justify-content: center;">
              <button 
                type="button" 
                class="btn-table-action btn-edit-loan"
                data-id="{{ $loan->id }}"
                data-user="{{ $loan->user->name ?? '' }}"
                data-book="{{ $loan->book->title ?? '' }}"
                data-due-date="{{ \Carbon\Carbon::parse($loan->due_date)->format('Y-m-d') }}"
                data-return-date="{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('Y-m-d') : '' }}"
                data-status="{{ $displayStatus }}"
                data-notes="{{ $loan->notes ?? '' }}"
                title="Edit Transaksi / Perpanjang"
              >
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <span>Edit</span>
              </button>

              <form action="{{ route('admin.loans.destroy', $loan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus transaksi peminjaman ini? Stok buku akan dikembalikan otomatis.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-table-action btn-delete" title="Hapus Transaksi">
                  <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                  <span>Hapus</span>
                </button>
              </form>
            </div>
          </td>
          @endif
        </tr>
      @empty
        <tr>
          <td colspan="{{ Auth::check() && Auth::user()->isAdmin() ? 7 : 6 }}" style="text-align: center; padding: 36px; color: #64748b;">
            Belum ada data transaksi peminjaman yang tercatat.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</section>

@if(Auth::check() && Auth::user()->isAdmin())
<!-- Modal Catat Peminjaman Baru -->
<div class="modal-overlay" id="addLoanModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Catat Peminjaman Buku</h3>
      <button type="button" class="btn-close-custom" id="closeAddLoanModal">&times;</button>
    </div>
    <form action="{{ route('admin.loans.store') }}" method="POST">
      @csrf
      <div class="modal-body-custom">
        <div class="form-field-custom">
          <label for="loanUser">Nama Pengunjung / Peminjam *</label>
          <select id="loanUser" name="user_id" required>
            <option value="">-- Pilih Pengunjung Peminjam --</option>
            @foreach ($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->member_id ?? $user->email }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-field-custom">
          <label for="loanBook">Buku yang Dipinjam *</label>
          <select id="loanBook" name="book_id" required>
            <option value="">-- Pilih Buku yang Tersedia --</option>
            @foreach ($availableBooks as $book)
              <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                {{ $book->title }} - {{ $book->author }} [Stok: {{ $book->available }}]
              </option>
            @endforeach
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-field-custom">
            <label for="loanDate">Tanggal Peminjaman *</label>
            <input type="date" id="loanDate" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required>
          </div>
          <div class="form-field-custom">
            <label for="dueDate">Jatuh Tempo (Batas Kembali) *</label>
            <input type="date" id="dueDate" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
          </div>
        </div>

        <div class="form-field-custom">
          <label for="loanNotes">Catatan Peminjaman (Opsional)</label>
          <textarea id="loanNotes" name="notes" rows="3" placeholder="Contoh: Peminjaman untuk referensi skripsi / tugas">{{ old('notes') }}</textarea>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelAddLoanModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Catat Peminjaman</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Peminjaman (Perpanjangan / Pengembalian) -->
<div class="modal-overlay" id="editLoanModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Edit Transaksi Peminjaman</h3>
      <button type="button" class="btn-close-custom" id="closeEditLoanModal">&times;</button>
    </div>
    <form id="editLoanForm" action="" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body-custom">
        <div class="modal-info-box">
          <div class="modal-info-row">
            <span class="modal-info-label">Peminjam:</span>
            <span class="modal-info-val" id="editInfoUser">-</span>
          </div>
          <div class="modal-info-row">
            <span class="modal-info-label">Buku:</span>
            <span class="modal-info-val" id="editInfoBook">-</span>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-field-custom">
            <label for="editDueDateInput">Jatuh Tempo (Perpanjangan) *</label>
            <input type="date" id="editDueDateInput" name="due_date" required>
          </div>

          <div class="form-field-custom">
            <label for="editStatusSelect">Status Peminjaman *</label>
            <select id="editStatusSelect" name="status" required>
              <option value="Dipinjam">Dipinjam</option>
              <option value="Terlambat">Terlambat</option>
              <option value="Dikembalikan">Dikembalikan</option>
            </select>
          </div>
        </div>

        <div class="form-field-custom" id="returnDateFieldGroup">
          <label for="editReturnDateInput">Tanggal Riil Pengembalian</label>
          <input type="date" id="editReturnDateInput" name="return_date" value="{{ date('Y-m-d') }}">
        </div>

        <div class="form-field-custom">
          <label for="editNotesInput">Catatan</label>
          <textarea id="editNotesInput" name="notes" rows="3" placeholder="Catatan tambahan mengenai kondisi buku atau transaksi..."></textarea>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelEditLoanModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Modal Add Loan Logic
    const addModal = document.getElementById('addLoanModal');
    const openAddBtn = document.getElementById('openAddLoanModal');
    const closeAddBtn = document.getElementById('closeAddLoanModal');
    const cancelAddBtn = document.getElementById('cancelAddLoanModal');

    if (openAddBtn && addModal) {
      openAddBtn.addEventListener('click', () => addModal.classList.add('active'));
    }
    if (closeAddBtn && addModal) {
      closeAddBtn.addEventListener('click', () => addModal.classList.remove('active'));
    }
    if (cancelAddBtn && addModal) {
      cancelAddBtn.addEventListener('click', () => addModal.classList.remove('active'));
    }
    if (addModal) {
      addModal.addEventListener('click', (e) => {
        if (e.target === addModal) addModal.classList.remove('active');
      });
    }

    // Modal Edit Loan Logic
    const editModal = document.getElementById('editLoanModal');
    const closeEditBtn = document.getElementById('closeEditLoanModal');
    const cancelEditBtn = document.getElementById('cancelEditLoanModal');
    const editForm = document.getElementById('editLoanForm');
    const editInfoUser = document.getElementById('editInfoUser');
    const editInfoBook = document.getElementById('editInfoBook');
    const editDueDateInput = document.getElementById('editDueDateInput');
    const editStatusSelect = document.getElementById('editStatusSelect');
    const editReturnDateInput = document.getElementById('editReturnDateInput');
    const editNotesInput = document.getElementById('editNotesInput');
    const returnDateFieldGroup = document.getElementById('returnDateFieldGroup');

    // Toggle return_date field based on status
    function checkStatusVisibility() {
      if (editStatusSelect.value === 'Dikembalikan') {
        if (!editReturnDateInput.value) {
          const today = new Date().toISOString().split('T')[0];
          editReturnDateInput.value = today;
        }
        returnDateFieldGroup.style.display = 'flex';
      } else {
        returnDateFieldGroup.style.display = 'none';
      }
    }

    if (editStatusSelect) {
      editStatusSelect.addEventListener('change', checkStatusVisibility);
    }

    document.querySelectorAll('.btn-edit-loan').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const user = btn.getAttribute('data-user');
        const book = btn.getAttribute('data-book');
        const dueDate = btn.getAttribute('data-due-date');
        const returnDate = btn.getAttribute('data-return-date');
        const status = btn.getAttribute('data-status');
        const notes = btn.getAttribute('data-notes');

        editForm.action = `{{ url('/admin/peminjaman') }}/${id}`;
        editInfoUser.textContent = user;
        editInfoBook.textContent = book;
        editDueDateInput.value = dueDate;
        editStatusSelect.value = status;
        editReturnDateInput.value = returnDate;
        editNotesInput.value = notes;

        checkStatusVisibility();
        editModal.classList.add('active');
      });
    });

    if (closeEditBtn && editModal) {
      closeEditBtn.addEventListener('click', () => editModal.classList.remove('active'));
    }
    if (cancelEditBtn && editModal) {
      cancelEditBtn.addEventListener('click', () => editModal.classList.remove('active'));
    }
    if (editModal) {
      editModal.addEventListener('click', (e) => {
        if (e.target === editModal) editModal.classList.remove('active');
      });
    }

    // Client-side search & filtering
    const searchInput = document.getElementById('searchLoanInput');
    const statusFilter = document.getElementById('statusFilter');
    const btnResetFilter = document.getElementById('btnResetFilter');
    const rows = document.querySelectorAll('.loan-row');

    function filterTable() {
      const term = (searchInput?.value || '').toLowerCase().trim();
      const status = statusFilter?.value || 'Semua';

      rows.forEach(row => {
        const user = row.getAttribute('data-user') || '';
        const book = row.getAttribute('data-book') || '';
        const rowStatus = row.getAttribute('data-status') || '';

        const matchesSearch = !term || user.includes(term) || book.includes(term);
        const matchesStatus = (status === 'Semua') || (rowStatus === status);

        if (matchesSearch && matchesStatus) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    if (btnResetFilter) {
      btnResetFilter.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = 'Semua';
        filterTable();
      });
    }
  });
</script>
@endsection
