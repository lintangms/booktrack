@extends('layouts.app')

@section('title', 'Pengembalian & Denda')
@section('page_title', 'Pengembalian Buku')

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
    .modal-overlay.active { display: flex; }
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
      font-size: 22px;
      line-height: 1;
    }
    .btn-close-custom:hover { color: #0f172a; }
    .modal-body-custom {
      padding: 20px 24px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      max-height: 75vh;
      overflow-y: auto;
    }
    .form-field-custom {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .form-field-custom label {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
    }
    .form-field-custom input, .form-field-custom select, .form-field-custom textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: #1e293b;
      box-sizing: border-box;
    }
    .form-field-custom input:focus, .form-field-custom select:focus, .form-field-custom textarea:focus {
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
      padding: 9px 16px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #475569;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-submit-custom {
      padding: 9px 20px;
      border-radius: 8px;
      border: none;
      background: #059669;
      color: #ffffff;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-submit-custom:hover { background: #047857; }
    /* Action Buttons */
    .action-btn-group {
      display: flex;
      align-items: center;
      gap: 6px;
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
      text-decoration: none;
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
    .btn-table-action:disabled,
    .btn-table-action.disabled {
      opacity: 0.45;
      cursor: not-allowed;
      pointer-events: none;
    }
    .badge-denda-lunas {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 600;
      background: #dcfce7;
      color: #166534;
    }
    .badge-denda-belum {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 600;
      background: #fee2e2;
      color: #991b1b;
    }
    .badge-denda-bebas {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 600;
      background: #f1f5f9;
      color: #475569;
    }
  </style>
@endsection

@section('content')
<div class="page-header-row">
  <div>
    <h1 class="page-title">{{ Auth::check() && Auth::user()->isAdmin() ? 'Pengembalian & Denda' : 'Pengembalian Saya' }}</h1>
    <p class="page-subtitle">{{ Auth::check() && Auth::user()->isAdmin() ? 'Kelola pengembalian buku, kalkulasi denda keterlambatan, dan pemeriksaan fisik koleksi.' : 'Riwayat pengembalian buku Anda beserta status denda dan kondisi buku.' }}</p>
  </div>
  @if(Auth::check() && Auth::user()->isAdmin())
  <div>
    <button type="button" class="btn-primary-action" id="openAddReturnModal">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
        <path d="M3 3v5h5"></path>
      </svg>
      <span>Tambah Pengembalian</span>
    </button>
  </div>
  @endif
</div>

<section class="books-summary-card">
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Pengembalian</span>
    <span class="summary-stat-number">{{ $stats['total'] ?? 0 }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Denda Terkumpul</span>
    <span class="summary-stat-number" style="color: #059669;">Rp {{ number_format($stats['paid_fine'] ?? 0, 0, ',', '.') }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Denda Belum Bayar</span>
    <span class="summary-stat-number" style="color: #dc2626;">Rp {{ number_format($stats['unpaid'] ?? 0, 0, ',', '.') }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Kondisi Buku Baik</span>
    <span class="summary-stat-number">{{ $stats['good_books'] ?? 0 }}</span>
  </div>
</section>

<!-- Filter & Search Toolbar -->
<section class="table-toolbar">
  <form method="GET" action="{{ Auth::user()->isAdmin() ? route('admin.returns.index') : route('user.returns.index') }}" style="display: flex; gap: 12px; flex: 1; flex-wrap: wrap;">
    <div class="search-box-wrapper" style="flex: 1; min-width: 240px;">
      <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
      <input 
        type="text" 
        name="search"
        class="search-input" 
        placeholder="Cari kode kembali, nama peminjam, atau judul buku..."
        value="{{ request('search') }}"
      >
    </div>

    <select name="condition" class="filter-select" onchange="this.form.submit()" aria-label="Filter Kondisi">
      <option value="">Semua Kondisi</option>
      <option value="Baik" {{ request('condition') === 'Baik' ? 'selected' : '' }}>Kondisi: Baik</option>
      <option value="Rusak" {{ request('condition') === 'Rusak' ? 'selected' : '' }}>Kondisi: Rusak</option>
      <option value="Hilang" {{ request('condition') === 'Hilang' ? 'selected' : '' }}>Kondisi: Hilang</option>
    </select>

    <select name="fine_status" class="filter-select" onchange="this.form.submit()" aria-label="Filter Status Denda">
      <option value="">Semua Status Denda</option>
      <option value="Bebas Denda" {{ request('fine_status') === 'Bebas Denda' ? 'selected' : '' }}>Bebas Denda</option>
      <option value="Lunas" {{ request('fine_status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
      <option value="Belum Lunas" {{ request('fine_status') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
    </select>

    @if(request('search') || request('condition') || request('fine_status'))
      <a href="{{ Auth::user()->isAdmin() ? route('admin.returns.index') : route('user.returns.index') }}" class="btn-reset-filter" style="text-decoration: none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
          <path d="M3 3v5h5"></path>
        </svg>
        <span>Reset</span>
      </a>
    @endif
  </form>
</section>

<!-- Tabel Data Pengembalian (Read) -->
<section class="table-container">
  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 14%;">KODE KEMBALI</th>
        <th style="width: 20%;">PEMINJAM</th>
        <th style="width: 22%;">BUKU</th>
        <th style="width: 12%;">TGL KEMBALI</th>
        <th style="width: 10%;">KONDISI</th>
        <th style="width: 12%;">DENDA</th>
        @if(Auth::check() && Auth::user()->isAdmin())
          <th style="width: 10%; text-align: center;">AKSI</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @forelse ($returns as $ret)
        <tr>
          <td><span style="font-family: monospace; font-weight: 700; color: #059669;">{{ $ret->return_code }}</span></td>
          <td>
            <div style="font-weight: 700; color: #0f172a;">{{ $ret->loan?->user?->name ?? 'Pengguna Dihapus' }}</div>
            <div style="font-size: 12px; color: #64748b;">NIM: {{ $ret->loan?->user?->member_id ?? '-' }}</div>
          </td>
          <td>
            <div style="font-weight: 600; color: #0f172a;">{{ $ret->loan?->book?->title ?? 'Buku Dihapus' }}</div>
            <div style="font-size: 12px; color: #64748b;">Pinjam: {{ $ret->loan?->loan_code ?? '-' }}</div>
          </td>
          <td><span style="font-size: 13px; color: #334155;">{{ \Carbon\Carbon::parse($ret->return_date)->format('d/m/Y') }}</span></td>
          <td>
            <span class="category-badge" style="{{ $ret->condition !== 'Baik' ? 'background: #fee2e2; color: #991b1b;' : '' }}">
              {{ $ret->condition }}
            </span>
          </td>
          <td>
            <div>
              <span style="font-weight: 700; font-size: 13px; color: {{ $ret->fine_amount > 0 ? '#dc2626' : '#059669' }};">
                Rp {{ number_format($ret->fine_amount, 0, ',', '.') }}
              </span>
            </div>
            <div>
              @if ($ret->fine_status === 'Lunas')
                <span class="badge-denda-lunas">Lunas</span>
              @elseif ($ret->fine_status === 'Belum Lunas')
                <span class="badge-denda-belum">Belum Lunas</span>
              @else
                <span class="badge-denda-bebas">Bebas Denda</span>
              @endif
            </div>
          </td>
          @if(Auth::check() && Auth::user()->isAdmin())
          <td>
            <div class="action-btn-group" style="justify-content: center;">
              <!-- Tombol Edit (Update) -->
              <button 
                type="button" 
                class="btn-table-action btn-edit-return"
                data-id="{{ $ret->id }}"
                data-code="{{ $ret->return_code }}"
                data-fine="{{ $ret->fine_amount }}"
                data-status="{{ $ret->fine_status }}"
                data-notes="{{ $ret->notes }}"
                title="Edit Pengembalian"
              >
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span>Edit</span>
              </button>

              <!-- Tombol Hapus (Delete) -->
              <form action="{{ route('admin.returns.destroy', $ret->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengembalian \'{{ $ret->return_code }}\'?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-table-action btn-delete" title="Hapus Pengembalian">
                  <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
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
          <td colspan="{{ Auth::check() && Auth::user()->isAdmin() ? 7 : 6 }}" style="text-align: center; padding: 32px; color: #64748b;">
            Tidak ditemukan data transaksi pengembalian yang sesuai.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</section>

@if(Auth::check() && Auth::user()->isAdmin())
<!-- MODAL PROSES PENGEMBALIAN BUKU (CREATE) -->
<div class="modal-overlay" id="addReturnModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Tambah Pengembalian Buku</h3>
      <button type="button" class="btn-close-custom" id="closeAddReturnModal">&times;</button>
    </div>
    <form action="{{ route('admin.returns.store') }}" method="POST">
      @csrf
      <div class="modal-body-custom">
        <div class="form-field-custom">
          <label>Pilih Transaksi Peminjaman Aktif *</label>
          <select name="loan_id" required>
            <option value="">-- Pilih Transaksi Pinjam --</option>
            @foreach ($activeLoans as $al)
              <option value="{{ $al->id }}">
                {{ $al->loan_code }} | {{ $al->user?->name }} ({{ $al->book?->title }}) - Jatuh Tempo: {{ \Carbon\Carbon::parse($al->due_date)->format('d/m/Y') }}
              </option>
            @endforeach
          </select>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-field-custom">
            <label>Tanggal Dikembalikan *</label>
            <input type="date" name="return_date" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="form-field-custom">
            <label>Kondisi Buku Fisik *</label>
            <select name="condition" required>
              <option value="Baik" selected>Kondisi Baik</option>
              <option value="Rusak">Rusak (+Denda Rp 20.000)</option>
              <option value="Hilang">Hilang (+Denda Rp 50.000)</option>
            </select>
          </div>
        </div>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; font-size: 13px; color: #475569;">
          <strong>Ketentuan Denda Otomatis:</strong><br>
          • Keterlambatan: <strong>Rp 1.000 / hari</strong> setelah tanggal jatuh tempo.<br>
          • Stok buku yang dikembalikan akan otomatis bertambah kembali ke sistem.
        </div>
        <div class="form-field-custom">
          <label>Catatan Petugas (Opsional)</label>
          <textarea name="notes" rows="2" placeholder="Catatan kondisi buku atau konfirmasi denda..."></textarea>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelAddReturnModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Konfirmasi Pengembalian</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT PENGEMBALIAN (UPDATE) -->
<div class="modal-overlay" id="editReturnModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Edit Status Pengembalian</h3>
      <button type="button" class="btn-close-custom" id="closeEditReturnModal">&times;</button>
    </div>
    <form id="editReturnForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body-custom">
        <div class="form-field-custom">
          <label>Kode Pengembalian</label>
          <input type="text" id="edit_return_code" readonly style="background: #f1f5f9; color: #64748b;">
        </div>
        <div class="form-field-custom">
          <label>Status Pembayaran Denda *</label>
          <select id="edit_fine_status" name="fine_status" required>
            <option value="Bebas Denda">Bebas Denda</option>
            <option value="Lunas">Lunas</option>
            <option value="Belum Lunas">Belum Lunas</option>
          </select>
        </div>
        <div class="form-field-custom">
          <label>Catatan</label>
          <textarea id="edit_return_notes" name="notes" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelEditReturnModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Perbarui Pengembalian</button>
      </div>
    </form>
  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const addModal = document.getElementById('addReturnModal');
    const openAddBtn = document.getElementById('openAddReturnModal');
    const closeAddBtn = document.getElementById('closeAddReturnModal');
    const cancelAddBtn = document.getElementById('cancelAddReturnModal');

    if (openAddBtn && addModal) openAddBtn.addEventListener('click', () => addModal.classList.add('active'));
    if (closeAddBtn && addModal) closeAddBtn.addEventListener('click', () => addModal.classList.remove('active'));
    if (cancelAddBtn && addModal) cancelAddBtn.addEventListener('click', () => addModal.classList.remove('active'));

    const editModal = document.getElementById('editReturnModal');
    const closeEditBtn = document.getElementById('closeEditReturnModal');
    const cancelEditBtn = document.getElementById('cancelEditReturnModal');
    const editForm = document.getElementById('editReturnForm');

    if (closeEditBtn && editModal) closeEditBtn.addEventListener('click', () => editModal.classList.remove('active'));
    if (cancelEditBtn && editModal) cancelEditBtn.addEventListener('click', () => editModal.classList.remove('active'));

    document.querySelectorAll('.btn-edit-return').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        editForm.action = `/admin/pengembalian/${id}`;
        document.getElementById('edit_return_code').value = btn.dataset.code || '';
        document.getElementById('edit_fine_status').value = btn.dataset.status || 'Bebas Denda';
        document.getElementById('edit_return_notes').value = btn.dataset.notes || '';
        editModal.classList.add('active');
      });
    });

    window.addEventListener('click', (e) => {
      if (e.target === addModal) addModal.classList.remove('active');
      if (e.target === editModal) editModal.classList.remove('active');
    });
  });
</script>
@endsection
