@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endsection

@section('content')
<div class="page-header-row">
  <div>
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Selamat datang kembali, <strong>{{ $currentUser->name }}</strong>. Ringkasan aktivitas dan koleksi perpustakaan.</p>
  </div>
</div>

<section class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon-wrapper stat-icon-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
        <path d="M6 6h10"></path>
        <path d="M6 10h10"></path>
      </svg>
    </div>
    <div class="stat-info">
      <span class="stat-label">Total Buku</span>
      <span class="stat-value">{{ $totalBooks }}</span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon-wrapper stat-icon-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
        <circle cx="9" cy="7" r="4"></circle>
        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
      </svg>
    </div>
    <div class="stat-info">
      <span class="stat-label">Total Anggota</span>
      <span class="stat-value">{{ $totalMembers }}</span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon-wrapper stat-icon-orange">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M7 10h14l-4-4"></path>
        <path d="M17 14H3l4 4"></path>
      </svg>
    </div>
    <div class="stat-info">
      <span class="stat-label">Sedang Dipinjam</span>
      <span class="stat-value">{{ $borrowedBooks }}</span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon-wrapper stat-icon-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
    </div>
    <div class="stat-info">
      <span class="stat-label">Buku Terlambat</span>
      <span class="stat-value">{{ $overdueBooks }}</span>
    </div>
  </div>
</section>

<section class="dashboard-chart-card">
  <div class="chart-card-header">
    <div>
      <h2 class="chart-card-title">Aktivitas Peminjaman</h2>
      <p class="chart-card-subtitle">Enam bulan terakhir</p>
    </div>
    <div>
      <select class="select-pill" aria-label="Tahun">
        <option value="2026" selected>2026</option>
        <option value="2025">2025</option>
      </select>
    </div>
  </div>

  <div class="chart-bars-container">
    @foreach ($chartData as $bar)
      <div class="chart-bar-group">
        <div class="chart-bar-pill {{ $bar['highlight'] ? 'highlight' : '' }}" style="height: {{ $bar['value'] }}%;"></div>
        <span class="chart-bar-label">{{ $bar['label'] }}</span>
      </div>
    @endforeach
  </div>
</section>
@endsection
