@extends('layouts.app')

@section('title', 'Data Pengunjung')
@section('page_title', 'Data Pengunjung')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/data-buku.css') }}">
  <style>
    /* Styling Metrik & Avatar Anggota */
    .member-info-col {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .member-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
    }

    .member-avatar.avatar-alt {
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
      box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
    }

    .member-meta {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .member-name {
      font-size: 0.9rem;
      font-weight: 600;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .member-email {
      font-size: 0.8rem;
      color: #64748b;
    }

    .badge-current-user {
      font-size: 0.65rem;
      font-weight: 700;
      padding: 1px 6px;
      border-radius: 4px;
      background-color: #f1f5f9;
      color: #475569;
      border: 1px solid #cbd5e1;
    }

    .nim-badge {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      font-size: 0.825rem;
      font-weight: 600;
      color: #334155;
      background: #f8fafc;
      padding: 4px 8px;
      border-radius: 6px;
      border: 1px solid #e2e8f0;
      display: inline-block;
    }

    .dept-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 0.825rem;
      color: #475569;
      background-color: #f8fafc;
      padding: 4px 10px;
      border-radius: 6px;
      border: 1px solid #e2e8f0;
    }

    .role-badge {
      font-size: 0.75rem;
      font-weight: 600;
      padding: 3px 8px;
      border-radius: 6px;
      display: inline-block;
      background: #f1f5f9;
      color: #475569;
    }

    .role-badge.role-admin {
      background: #eff6ff;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
    }

    /* Status Pills */
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

    .badge-status-pill.status-aktif {
      background-color: #ecfdf5;
      color: #047857;
      border: 1px solid #a7f3d0;
    }

    .badge-status-pill.status-nonaktif {
      background-color: #fef2f2;
      color: #b91c1c;
      border: 1px solid #fecaca;
    }

    .status-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background-color: currentColor;
    }

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

    /* Modal System */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background-color: rgba(15, 23, 42, 0.55);
      z-index: 1000;
      align-items: center;
      justify-content: center;
      padding: 20px;
      backdrop-filter: blur(2px);
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

    .modal-box.modal-sm {
      max-width: 440px;
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
      border-radius: 6px;
      transition: all 0.15s ease;
    }

    .btn-close-custom:hover {
      color: #0f172a;
      background-color: #f1f5f9;
    }

    .modal-body-custom {
      padding: 20px 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
      max-height: 75vh;
      overflow-y: auto;
    }

    .form-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
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
    .form-field-custom select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: #1e293b;
      box-sizing: border-box;
      background-color: #ffffff;
      transition: all 0.15s ease;
    }

    .form-field-custom input:focus,
    .form-field-custom select:focus {
      outline: none;
      border-color: #059669;
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }

    .form-hint {
      font-size: 12px;
      color: #64748b;
    }

    .info-alert-box {
      background-color: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #166534;
      border-radius: 8px;
      padding: 12px 14px;
      font-size: 13px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      line-height: 1.4;
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
      transition: all 0.15s ease;
    }

    .btn-cancel-custom:hover {
      background: #f1f5f9;
      color: #0f172a;
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
      transition: all 0.15s ease;
    }

    .btn-submit-custom:hover {
      background: #047857;
    }

    .btn-danger-custom {
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      background: #dc2626;
      color: #ffffff;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.15s ease;
    }

    .btn-danger-custom:hover {
      background: #b91c1c;
    }

    .checkbox-field-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 4px;
      cursor: pointer;
    }

    .checkbox-field-wrapper input[type="checkbox"] {
      width: 16px;
      height: 16px;
      cursor: pointer;
      accent-color: #059669;
    }

    .checkbox-field-wrapper label {
      font-size: 13px;
      color: #334155;
      font-weight: 500;
      cursor: pointer;
    }
  </style>
@endsection

@section('content')
<div class="page-header-row">
  <div>
    <h1 class="page-title">Data Pengunjung Perpustakaan</h1>
    <p class="page-subtitle">Kelola informasi pengunjung, data mahasiswa, dan akun pengguna BookTrack.</p>
  </div>
  <div>
    <button type="button" class="btn-primary-action" id="openAddMemberModal">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>Tambah Pengunjung</span>
    </button>
  </div>
</div>

<!-- Card Ringkasan Metrik Pengunjung -->
<section class="books-summary-card">
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Pengunjung</span>
    <span class="summary-stat-number">{{ $totalMembers }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Pengunjung Aktif</span>
    <span class="summary-stat-number" style="color: #059669;">{{ $activeMembers }}</span>
  </div>
  <div class="summary-stat-item">
    <span class="summary-stat-label">Total Mahasiswa</span>
    <span class="summary-stat-number" style="color: #0284c7;">{{ $totalStudents }}</span>
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
      id="searchMemberInput" 
      class="search-input" 
      placeholder="Cari berdasarkan nama, NIM, email, atau program studi..."
      value="{{ request('search') }}"
      autocomplete="off"
    >
  </div>

  <select class="filter-select" id="statusFilter" aria-label="Filter Status">
    <option value="Semua" {{ request('status') === 'Semua' || !request('status') ? 'selected' : '' }}>Semua Status</option>
    <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
    <option value="Nonaktif" {{ request('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
  </select>

  <button type="button" class="btn-reset-filter" id="btnResetFilter">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
      <path d="M3 3v5h5"></path>
    </svg>
    <span>Reset</span>
  </button>
</section>

<!-- Tabel Data Anggota -->
<section class="table-container">
  <table class="data-table" id="membersTable">
    <thead>
      <tr>
        <th style="width: 28%;">ANGGOTA</th>
        <th style="width: 16%;">NIM / ID</th>
        <th style="width: 18%;">PROGRAM STUDI</th>
        <th style="width: 14%;">NO. TELEPON / WA</th>
        <th style="width: 10%;">STATUS</th>
        <th style="width: 14%; text-align: center;">AKSI</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($members as $member)
        <tr class="member-row"
            data-id="{{ $member->id }}"
            data-name="{{ strtolower($member->name) }}"
            data-member_id="{{ strtolower($member->member_id ?? '') }}"
            data-email="{{ strtolower($member->email) }}"
            data-department="{{ strtolower($member->department ?? '') }}"
            data-phone="{{ $member->phone ?? '' }}"
            data-status="{{ $member->status ?? 'Aktif' }}"
            data-role="{{ $member->role ?? 'Pengunjung' }}">
          <td>
            <div class="member-info-col">
              <div class="member-avatar {{ $member->isAdmin() ? 'avatar-alt' : '' }}">
                {{ $member->initials }}
              </div>
              <div class="member-meta">
                <span class="member-name">
                  {{ $member->name }}
                  @if (Auth::id() === $member->id)
                    <span class="badge-current-user">Anda</span>
                  @endif
                </span>
                <span class="member-email">{{ $member->email }}</span>
              </div>
            </div>
          </td>
          <td>
            @if ($member->member_id)
              <span class="nim-badge">{{ $member->member_id }}</span>
            @else
              <span style="color: #94a3b8; font-size: 0.85rem;">-</span>
            @endif
          </td>
          <td>
            @if ($member->department)
              <span class="dept-badge">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                  <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
                <span>{{ $member->department }}</span>
              </span>
            @else
              <span style="color: #94a3b8; font-size: 0.85rem;">-</span>
            @endif
          </td>
          <td>
            @if ($member->phone)
              <span style="font-size: 0.85rem; color: #334155;">{{ $member->phone }}</span>
            @else
              <span style="color: #94a3b8; font-size: 0.85rem;">-</span>
            @endif
          </td>
          <td>
            @php
              $status = $member->status ?? 'Aktif';
            @endphp
            <span class="badge-status-pill {{ $status === 'Aktif' ? 'status-aktif' : 'status-nonaktif' }}">
              <span class="status-dot"></span>
              <span>{{ $status }}</span>
            </span>
          </td>
          <td>
            <div class="action-btn-group" style="justify-content: center;">
              <button 
                type="button" 
                class="btn-table-action btn-edit-member"
                data-id="{{ $member->id }}"
                data-name="{{ $member->name }}"
                data-member_id="{{ $member->member_id ?? '' }}"
                data-email="{{ $member->email }}"
                data-department="{{ $member->department ?? '' }}"
                data-phone="{{ $member->phone ?? '' }}"
                data-status="{{ $member->status ?? 'Aktif' }}"
                title="Edit Data Pengunjung">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span>Edit</span>
              </button>

              @if (Auth::id() === $member->id)
                <button 
                  type="button" 
                  class="btn-table-action disabled" 
                  title="Anda tidak dapat menghapus akun Anda sendiri"
                  disabled>
                  <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                  <span>Hapus</span>
                </button>
              @else
                <button 
                  type="button" 
                  class="btn-table-action btn-delete btn-delete-member"
                  data-id="{{ $member->id }}"
                  data-name="{{ $member->name }}"
                  title="Hapus Pengunjung">
                  <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                  <span>Hapus</span>
                </button>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr id="emptyRow">
          <td colspan="6" style="text-align: center; padding: 36px; color: #64748b;">
            Belum ada data anggota yang terdaftar.
          </td>
        </tr>
      @endforelse
      <tr id="noResultsRow" style="display: none;">
        <td colspan="6" style="text-align: center; padding: 36px; color: #64748b;">
          Tidak ditemukan anggota yang sesuai dengan kriteria pencarian.
        </td>
      </tr>
    </tbody>
  </table>
</section>

<!-- Modal Tambah Anggota -->
<div class="modal-overlay" id="addMemberModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Tambah Pengunjung Perpustakaan</h3>
      <button type="button" class="btn-close-custom" id="closeAddMemberModal">&times;</button>
    </div>
    <form action="{{ route('members.store') }}" method="POST">
      @csrf
      <div class="modal-body-custom">
        <div class="info-alert-box">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
          </svg>
          <div>
            Akun pengunjung baru otomatis menggunakan password default: <strong>password123</strong> dengan role <strong>Pengunjung</strong>.
          </div>
        </div>

        <div class="form-field-custom">
          <label for="addName">Nama Lengkap *</label>
          <input type="text" id="addName" name="name" placeholder="Contoh: Lintang Maulana" required>
        </div>

        <div class="form-grid-2">
          <div class="form-field-custom">
            <label for="addMemberId">NIM / Nomor Induk *</label>
            <input type="text" id="addMemberId" name="member_id" placeholder="Contoh: 231104028" required>
          </div>
          <div class="form-field-custom">
            <label for="addEmail">Alamat Email *</label>
            <input type="email" id="addEmail" name="email" placeholder="contoh@kampus.ac.id" required>
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-field-custom">
            <label for="addDepartment">Program Studi / Jurusan</label>
            <input type="text" id="addDepartment" name="department" placeholder="Contoh: Teknik Informatika">
          </div>
          <div class="form-field-custom">
            <label for="addPhone">No. Telepon / WA</label>
            <input type="text" id="addPhone" name="phone" placeholder="Contoh: 081234567890">
          </div>
        </div>

        <div class="form-field-custom">
          <label for="addStatus">Status Keanggotaan *</label>
          <select id="addStatus" name="status" required>
            <option value="Aktif" selected>Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
          </select>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelAddMemberModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Daftarkan Pengunjung</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Pengunjung -->
<div class="modal-overlay" id="editMemberModal">
  <div class="modal-box">
    <div class="modal-header-custom">
      <h3>Edit Data Pengunjung</h3>
      <button type="button" class="btn-close-custom" id="closeEditMemberModal">&times;</button>
    </div>
    <form id="editMemberForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body-custom">
        <div class="form-field-custom">
          <label for="editName">Nama Lengkap *</label>
          <input type="text" id="editName" name="name" required>
        </div>

        <div class="form-grid-2">
          <div class="form-field-custom">
            <label for="editMemberId">NIM / Nomor Induk *</label>
            <input type="text" id="editMemberId" name="member_id" required>
          </div>
          <div class="form-field-custom">
            <label for="editEmail">Alamat Email *</label>
            <input type="email" id="editEmail" name="email" required>
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-field-custom">
            <label for="editDepartment">Program Studi / Jurusan</label>
            <input type="text" id="editDepartment" name="department">
          </div>
          <div class="form-field-custom">
            <label for="editPhone">No. Telepon / WA</label>
            <input type="text" id="editPhone" name="phone">
          </div>
        </div>

        <div class="form-field-custom">
          <label for="editStatus">Status Keanggotaan *</label>
          <select id="editStatus" name="status" required>
            <option value="Aktif">Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
          </select>
        </div>

        <div class="checkbox-field-wrapper">
          <input type="checkbox" id="resetPasswordCheckbox" name="reset_password" value="1">
          <label for="resetPasswordCheckbox">Reset kata sandi pengguna ini ke default (<strong>password123</strong>)</label>
        </div>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelEditMemberModal">Batal</button>
        <button type="submit" class="btn-submit-custom">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Anggota -->
<div class="modal-overlay" id="deleteMemberModal">
  <div class="modal-box modal-sm">
    <div class="modal-header-custom">
      <h3 style="color: #dc2626; display: flex; align-items: center; gap: 8px;">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        Hapus Data Pengunjung
      </h3>
      <button type="button" class="btn-close-custom" id="closeDeleteMemberModal">&times;</button>
    </div>
    <form id="deleteMemberForm" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-body-custom">
        <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;">
          Apakah Anda yakin ingin menghapus pengunjung <strong id="deleteMemberName" style="color: #0f172a;"></strong>?
        </p>
        <p style="margin: 0; font-size: 13px; color: #64748b; line-height: 1.5;">
          Data yang telah dihapus tidak dapat dipulihkan kembali.
        </p>
      </div>
      <div class="modal-footer-custom">
        <button type="button" class="btn-cancel-custom" id="cancelDeleteMemberModal">Batal</button>
        <button type="submit" class="btn-danger-custom">Ya, Hapus Pengunjung</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // --- Referensi Elemen Modal ---
    const addModal = document.getElementById('addMemberModal');
    const openAddBtn = document.getElementById('openAddMemberModal');
    const closeAddBtn = document.getElementById('closeAddMemberModal');
    const cancelAddBtn = document.getElementById('cancelAddMemberModal');

    const editModal = document.getElementById('editMemberModal');
    const editForm = document.getElementById('editMemberForm');
    const closeEditBtn = document.getElementById('closeEditMemberModal');
    const cancelEditBtn = document.getElementById('cancelEditMemberModal');

    const deleteModal = document.getElementById('deleteMemberModal');
    const deleteForm = document.getElementById('deleteMemberForm');
    const deleteNameSpan = document.getElementById('deleteMemberName');
    const closeDeleteBtn = document.getElementById('closeDeleteMemberModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteMemberModal');

    // Buka Modal Tambah
    if (openAddBtn && addModal) {
      openAddBtn.addEventListener('click', () => addModal.classList.add('active'));
    }
    const closeAdd = () => addModal && addModal.classList.remove('active');
    if (closeAddBtn) closeAddBtn.addEventListener('click', closeAdd);
    if (cancelAddBtn) cancelAddBtn.addEventListener('click', closeAdd);

    // Tutup Modal Edit
    const closeEdit = () => editModal && editModal.classList.remove('active');
    if (closeEditBtn) closeEditBtn.addEventListener('click', closeEdit);
    if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEdit);

    // Tutup Modal Hapus
    const closeDelete = () => deleteModal && deleteModal.classList.remove('active');
    if (closeDeleteBtn) closeDeleteBtn.addEventListener('click', closeDelete);
    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDelete);

    // Event Delegasi untuk Tombol Edit
    document.querySelectorAll('.btn-edit-member').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const memberId = button.getAttribute('data-member_id');
        const email = button.getAttribute('data-email');
        const department = button.getAttribute('data-department');
        const phone = button.getAttribute('data-phone');
        const status = button.getAttribute('data-status');

        editForm.action = `{{ url('/data-anggota') }}/${id}`;
        document.getElementById('editName').value = name || '';
        document.getElementById('editMemberId').value = memberId || '';
        document.getElementById('editEmail').value = email || '';
        document.getElementById('editDepartment').value = department || '';
        document.getElementById('editPhone').value = phone || '';
        document.getElementById('editStatus').value = status || 'Aktif';
        document.getElementById('resetPasswordCheckbox').checked = false;

        editModal.classList.add('active');
      });
    });

    // Event Delegasi untuk Tombol Hapus
    document.querySelectorAll('.btn-delete-member').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        deleteForm.action = `{{ url('/data-anggota') }}/${id}`;
        if (deleteNameSpan) deleteNameSpan.textContent = `"${name}"`;

        deleteModal.classList.add('active');
      });
    });

    // Menutup Modal saat klik di area backdrop
    [addModal, editModal, deleteModal].forEach(modal => {
      if (modal) {
        modal.addEventListener('click', (e) => {
          if (e.target === modal) modal.classList.remove('active');
        });
      }
    });

    // Menutup Modal dengan tombol Escape (ESC)
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        [addModal, editModal, deleteModal].forEach(m => m && m.classList.remove('active'));
      }
    });

    // --- Live Search & Filter Client-Side ---
    const searchInput = document.getElementById('searchMemberInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('btnResetFilter');
    const tableRows = document.querySelectorAll('.member-row');
    const noResultsRow = document.getElementById('noResultsRow');

    function filterTable() {
      const searchTerm = (searchInput ? searchInput.value : '').toLowerCase().trim();
      const selectedStatus = statusFilter ? statusFilter.value : 'Semua';

      let visibleCount = 0;

      tableRows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const memberId = row.getAttribute('data-member_id') || '';
        const email = row.getAttribute('data-email') || '';
        const department = row.getAttribute('data-department') || '';
        const status = row.getAttribute('data-status') || '';

        const matchesSearch = !searchTerm || 
          name.includes(searchTerm) || 
          memberId.includes(searchTerm) || 
          email.includes(searchTerm) || 
          department.includes(searchTerm);

        const matchesStatus = (selectedStatus === 'Semua') || (status === selectedStatus);

        if (matchesSearch && matchesStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      if (noResultsRow) {
        noResultsRow.style.display = (visibleCount === 0 && tableRows.length > 0) ? '' : 'none';
      }
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);

    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = 'Semua';
        filterTable();
      });
    }
  });
</script>
@endsection
