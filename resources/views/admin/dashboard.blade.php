@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('styles')
<style>
    .quick-actions-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        margin-bottom: 32px;
    }
    .quick-actions-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }
    .action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        background: var(--primary-light);
        color: var(--primary-color);
        font-weight: 600;
        font-size: 14px;
        border: 1px solid rgba(11, 102, 62, 0.08);
        transition: all var(--transition-fast);
    }
    .action-btn:hover {
        background: var(--primary-color);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(11, 102, 62, 0.15);
    }
    .action-btn .icon {
        font-size: 20px;
    }
    .activity-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }
    @media (min-width: 1024px) {
        .activity-grid.cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        .activity-grid.cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .dashboard-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .dashboard-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .dashboard-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dashboard-card-body {
        padding: 20px;
        flex-grow: 1;
    }
    .subtext-detail {
        font-size: 11px;
        color: var(--text-light);
        margin-top: 4px;
        display: block;
    }
    .mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .mini-table th {
        text-align: left;
        padding: 8px 12px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-light);
        font-weight: 600;
    }
    .mini-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .mini-table tr:last-child td {
        border-bottom: none;
    }
    .mini-table tr:hover td {
        background-color: #f8fafc;
    }
    .stat-card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .stat-icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
</style>
@endsection

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>Dashboard Utama</h1>
            <p>Selamat datang kembali, <strong>{{ $user->name }}</strong>. Berikut adalah ringkasan panel pengelolaan untuk <strong>{{ $nama_desa ?? 'Desa Penebal' }}</strong>.</p>
        </div>
        <div class="page-actions">
            <span class="badge" style="background-color: var(--primary-light); color: var(--primary-color); border: 1px solid rgba(11, 102, 62, 0.1); padding: 8px 16px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--radius-sm);">
                📅 Hari ini: {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- 1. METRIK STATISTIK -->
    <!-- Grid Statistik disesuaikan berdasarkan Role -->
    @if($role === 'admin')
        <!-- Tampilan Gabungan untuk Super Admin -->
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">Ikhtisar Statistik Desa</h3>
        <div class="stats-grid" style="margin-bottom: 24px;">
            <div class="stat-card" style="border-left: 4px solid #d97706;">
                <div class="stat-card-header-flex">
                    <h3>Menunggu Verifikasi</h3>
                    <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #d97706;">{{ $surat_menunggu }}</div>
                <span class="subtext-detail">Dari total {{ $surat_total }} pengajuan surat</span>
            </div>
            
            <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
                <div class="stat-card-header-flex">
                    <h3>Jumlah Penduduk</h3>
                    <div class="stat-icon-wrapper" style="background: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <div class="stat-card-value">{{ $penduduk_total }}</div>
                <span class="subtext-detail">Laki-laki: {{ $penduduk_laki }} | Perempuan: {{ $penduduk_perempuan }}</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #0284c7;">
                <div class="stat-card-header-flex">
                    <h3>Berita Dirilis</h3>
                    <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><line x1="8" y1="11" x2="16" y2="11"></line><line x1="8" y1="15" x2="16" y2="15"></line><line x1="8" y1="7" x2="12" y2="7"></line></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #0284c7;">{{ $berita_published }}</div>
                <span class="subtext-detail">Dari total {{ $berita_total }} artikel dibuat</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
                <div class="stat-card-header-flex">
                    <h3>Pelaku UMKM</h3>
                    <div class="stat-icon-wrapper" style="background: #f3e8ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #8b5cf6;">{{ $umkm_total }}</div>
                <span class="subtext-detail">Penduduk terdaftar sebagai pemilik usaha</span>
            </div>
        </div>

        <div class="stats-grid" style="margin-bottom: 32px;">
            <div class="stat-card" style="border-left: 4px solid #059669;">
                <div class="stat-card-header-flex">
                    <h3>Surat Selesai</h3>
                    <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #059669;">{{ $surat_selesai }}</div>
                <span class="subtext-detail">Permohonan disetujui & bertanda tangan</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #ef4444;">
                <div class="stat-card-header-flex">
                    <h3>Surat Ditolak</h3>
                    <div class="stat-icon-wrapper" style="background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #ef4444;">{{ $surat_ditolak }}</div>
                <span class="subtext-detail">Verifikasi tidak valid atau data kurang lengkap</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #64748b;">
                <div class="stat-card-header-flex">
                    <h3>Galeri Unggahan</h3>
                    <div class="stat-icon-wrapper" style="background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #64748b;">{{ $galeri_total }}</div>
                <span class="subtext-detail">Dokumentasi foto kegiatan desa</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #ec4899;">
                <div class="stat-card-header-flex">
                    <h3>Audit Upload</h3>
                    <div class="stat-icon-wrapper" style="background: #fce7f3; color: #ec4899; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #ec4899;">{{ $upload_total }}</div>
                <span class="subtext-detail" style="color: #ec4899; font-weight: 600;">
                    ⚠️ {{ $upload_invalid }} Unggahan diblokir sistem
                </span>
            </div>
        </div>

    @elseif($role === 'operator_pelayanan')
        <!-- Tampilan untuk Operator Pelayanan -->
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">Metrik Pelayanan Surat</h3>
        <div class="stats-grid" style="margin-bottom: 32px;">
            <div class="stat-card" style="border-left: 4px solid #d97706;">
                <div class="stat-card-header-flex">
                    <h3>Menunggu Verifikasi</h3>
                    <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #d97706;">{{ $surat_menunggu }}</div>
                <span class="subtext-detail">Perlu pemeriksaan kelengkapan berkas</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #0284c7;">
                <div class="stat-card-header-flex">
                    <h3>Sedang Diproses</h3>
                    <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #0284c7;">{{ $surat_diproses }}</div>
                <span class="subtext-detail">Proses draf/penandatanganan berkas</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #059669;">
                <div class="stat-card-header-flex">
                    <h3>Surat Selesai</h3>
                    <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #059669;">{{ $surat_selesai }}</div>
                <span class="subtext-detail">Dokumen final telah diunduh pemohon</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
                <div class="stat-card-header-flex">
                    <h3>Bulan Ini</h3>
                    <div class="stat-icon-wrapper" style="background: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                </div>
                <div class="stat-card-value">{{ $surat_bulan_ini }}</div>
                <span class="subtext-detail">Total pengajuan masuk bulan ini</span>
            </div>
        </div>

    @elseif($role === 'operator_konten')
        <!-- Tampilan untuk Operator Konten -->
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">Statistik Konten & Informasi</h3>
        <div class="stats-grid" style="margin-bottom: 32px;">
            <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
                <div class="stat-card-header-flex">
                    <h3>Total Berita</h3>
                    <div class="stat-icon-wrapper" style="background: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><line x1="8" y1="11" x2="16" y2="11"></line><line x1="8" y1="15" x2="16" y2="15"></line><line x1="8" y1="7" x2="12" y2="7"></line></svg>
                    </div>
                </div>
                <div class="stat-card-value">{{ $berita_total }}</div>
                <span class="subtext-detail">Aktif: {{ $berita_published }} | Draf: {{ $berita_draft }}</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #0284c7;">
                <div class="stat-card-header-flex">
                    <h3>Foto Galeri</h3>
                    <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #0284c7;">{{ $galeri_total }}</div>
                <span class="subtext-detail">Unggahan foto kegiatan publik</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
                <div class="stat-card-header-flex">
                    <h3>Data Penduduk</h3>
                    <div class="stat-icon-wrapper" style="background: #f3e8ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #8b5cf6;">{{ $penduduk_total }}</div>
                <span class="subtext-detail">L: {{ $penduduk_laki }} | P: {{ $penduduk_perempuan }}</span>
            </div>

            <div class="stat-card" style="border-left: 4px solid #ec4899;">
                <div class="stat-card-header-flex">
                    <h3>Pelaku UMKM</h3>
                    <div class="stat-icon-wrapper" style="background: #fce7f3; color: #ec4899; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color: #ec4899;">{{ $umkm_total }}</div>
                <span class="subtext-detail">Warga dengan status UMKM aktif</span>
            </div>
        </div>
    @endif

    <!-- 2. PINTASAN AKSI CEPAT -->
    <div class="quick-actions-card">
        <h3 class="quick-actions-title">Akses Cepat & Tindakan</h3>
        <div class="quick-actions-grid">
            @if($role === 'admin' || $role === 'operator_pelayanan')
                <a href="{{ route('admin.surat.index') }}" class="action-btn">
                    <span class="icon" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </span>
                    <span>Kelola Pengajuan Surat</span>
                </a>
            @endif

            @if($role === 'admin' || $role === 'operator_konten')
                <a href="{{ route('admin.content.posts.create') }}" class="action-btn">
                    <span class="icon" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </span>
                    <span>Tulis Berita Baru</span>
                </a>
                <a href="{{ route('admin.content.galleries') }}" class="action-btn">
                    <span class="icon" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </span>
                    <span>Unggah Foto Galeri</span>
                </a>
                <a href="{{ route('admin.content.residents.create') }}" class="action-btn">
                    <span class="icon" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <span>Tambah Penduduk</span>
                </a>
                <a href="{{ route('admin.content.settings') }}" class="action-btn">
                    <span class="icon" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </span>
                    <span>Profil & Pengaturan Desa</span>
                </a>
            @endif
        </div>
    </div>

    <!-- 3. FEED AKTIVITAS DAN AUDIT LOG -->
    @if($role === 'admin')
        <!-- Layout 3 Kolom untuk Admin (Surat, Berita, Audit Log) -->
        <div class="activity-grid cols-3">
            <!-- Kolom Surat -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--primary-color);"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        Surat Masuk Terbaru
                    </h3>
                    <a href="{{ route('admin.surat.index') }}" style="font-size: 12px; color: var(--primary-color); font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                        Semua
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </a>
                </div>
                <div class="dashboard-card-body" style="padding: 0;">
                    @if(count($surat_terbaru) > 0)
                        <table class="mini-table">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surat_terbaru as $s)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;">{{ Str::limit($s->nama_lengkap, 15) }}</div>
                                            <div style="font-size: 10px; color: var(--text-light); font-family: monospace;">{{ $s->nomor_pengajuan }}</div>
                                        </td>
                                        <td>
                                            <span style="font-size: 11px;">
                                                {{ match($s->jenis_surat) { 'domisili' => 'Domisili', 'sktm' => 'SKTM', 'pindah' => 'Pindah', default => $s->jenis_surat } }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $s->status }}" style="font-size: 9px; padding: 2px 6px;">
                                                {{ $s->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 30px; text-align: center; color: var(--text-light); font-size: 13px;">Tidak ada permohonan surat masuk.</div>
                    @endif
                </div>
            </div>

            <!-- Kolom Berita -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--primary-color);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5-3h3m-3 3h3m-7.5-5.25L12 3m0 0l7.5 5.25M12 3v18M3 9v11.25A2.25 2.25 0 005.25 22.5h13.5a2.25 2.25 0 002.25-2.25V9" /></svg>
                        Berita Terbaru
                    </h3>
                    <a href="{{ route('admin.content.posts') }}" style="font-size: 12px; color: var(--primary-color); font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                        Semua
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </a>
                </div>
                <div class="dashboard-card-body" style="padding: 0;">
                    @if(count($berita_terbaru) > 0)
                        <table class="mini-table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($berita_terbaru as $b)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;">{{ Str::limit($b->title, 25) }}</div>
                                            <div style="font-size: 10px; color: var(--text-light);">{{ $b->created_at->translatedFormat('d M Y') }}</div>
                                        </td>
                                        <td>
                                            <span class="badge" style="font-size: 9px; padding: 2px 6px; background-color: {{ $b->status === 'published' ? 'var(--success-bg)' : '#e2e8f0' }}; color: {{ $b->status === 'published' ? 'var(--success-text)' : 'var(--text-light)' }};">
                                                {{ $b->status === 'published' ? 'Rilis' : 'Draf' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 30px; text-align: center; color: var(--text-light); font-size: 13px;">Belum ada berita yang ditulis.</div>
                    @endif
                </div>
            </div>

            <!-- Kolom Audit Log Keamanan -->
            <div class="dashboard-card" style="border-color: rgba(236, 72, 153, 0.2);">
                <div class="dashboard-card-header" style="background-color: #fff5f7; border-bottom: 1px solid rgba(236, 72, 153, 0.1);">
                    <h3 class="dashboard-card-title" style="color: #be185d; display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #be185d;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.956 11.956 0 0112 2.713z" /></svg>
                        Audit Keamanan Upload
                    </h3>
                    <span class="badge" style="background: #fce7f3; color: #be185d; font-size: 9px;">Realtime</span>
                </div>
                <div class="dashboard-card-body" style="padding: 0;">
                    @if(count($upload_logs) > 0)
                        <table class="mini-table">
                            <thead>
                                <tr>
                                    <th>Berkas / IP</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upload_logs as $l)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600; word-break: break-all; font-family: monospace; font-size: 11px;">
                                                {{ Str::limit($l->filename, 20) }}
                                            </div>
                                            <div style="font-size: 10px; color: var(--text-light);">
                                                IP: {{ $l->ip_address }} | {{ $l->uploaded_at->translatedFormat('d M H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($l->is_valid)
                                                <span class="badge" style="background-color: var(--success-bg); color: var(--success-text); font-size: 9px; padding: 2px 6px;">Lolos</span>
                                            @else
                                                <span class="badge" style="background-color: var(--danger-bg); color: var(--danger-text); font-size: 9px; padding: 2px 6px;" title="{{ is_array($l->validation_errors) ? implode(', ', $l->validation_errors) : $l->validation_errors }}">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 30px; text-align: center; color: var(--text-light); font-size: 13px;">Belum ada log unggahan berkas.</div>
                    @endif
                </div>
            </div>
        </div>

    @elseif($role === 'operator_pelayanan')
        <!-- Layout 1 Kolom untuk Operator Pelayanan (Daftar Pengajuan Terbaru) -->
        <div class="activity-grid cols-1">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--primary-color);"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        Permohonan Surat Masuk Terbaru
                    </h3>
                    <a href="{{ route('admin.surat.index') }}" class="btn btn-primary btn-sm" style="padding: 6px 12px; font-size: 11px; display: inline-flex; align-items: center; gap: 6px;">
                        Kelola Semua Surat
                    </a>
                </div>
                <div class="dashboard-card-body" style="padding: 0;">
                    @if(count($surat_terbaru) > 0)
                        <table class="mini-table">
                            <thead>
                                <tr>
                                    <th>Nomor Pengajuan</th>
                                    <th>NIK & Nama</th>
                                    <th>Jenis Surat</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Status</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surat_terbaru as $s)
                                    <tr>
                                        <td style="font-family: monospace; font-weight: 700; color: var(--primary-color);">{{ $s->nomor_pengajuan }}</td>
                                        <td>
                                            <div style="font-weight: 600;">{{ $s->nama_lengkap }}</div>
                                            <div style="font-size: 11px; color: var(--text-light);">NIK: {{ $s->nik }}</div>
                                        </td>
                                        <td>
                                            <span style="font-weight: 500;">
                                                {{ match($s->jenis_surat) { 'domisili' => 'Domisili', 'sktm' => 'SKTM', 'pindah' => 'Pindah', default => $s->jenis_surat } }}
                                            </span>
                                        </td>
                                        <td>{{ $s->created_at->translatedFormat('d M Y H:i') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $s->status }}" style="font-size: 11px;">
                                                {{ $s->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.surat.detail', $s->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                                Proses Berkas
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 40px; text-align: center; color: var(--text-light);">Tidak ada permohonan surat masuk yang menunggu tindakan.</div>
                    @endif
                </div>
            </div>
        </div>

    @elseif($role === 'operator_konten')
        <!-- Layout 2 Kolom untuk Operator Konten (Daftar Berita Terbaru & Ikhtisar Lain) -->
        <div class="activity-grid cols-2">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--primary-color);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5-3h3m-3 3h3m-7.5-5.25L12 3m0 0l7.5 5.25M12 3v18M3 9v11.25A2.25 2.25 0 005.25 22.5h13.5a2.25 2.25 0 002.25-2.25V9" /></svg>
                        Tulisan Berita Terbaru
                    </h3>
                    <a href="{{ route('admin.content.posts') }}" class="btn btn-primary btn-sm" style="padding: 6px 12px; font-size: 11px; display: inline-flex; align-items: center;">Kelola Berita</a>
                </div>
                <div class="dashboard-card-body" style="padding: 0;">
                    @if(count($berita_terbaru) > 0)
                        <table class="mini-table">
                            <thead>
                                <tr>
                                    <th>Judul Berita</th>
                                    <th>Tanggal Pembuatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($berita_terbaru as $b)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;">{{ Str::limit($b->title, 40) }}</div>
                                            <div style="font-size: 11px; color: var(--text-light);">Oleh: {{ $b->author->name ?? 'Staf Desa' }}</div>
                                        </td>
                                        <td>{{ $b->created_at->translatedFormat('d M Y H:i') }}</td>
                                        <td>
                                            <span class="badge" style="font-size: 10px; background-color: {{ $b->status === 'published' ? 'var(--success-bg)' : '#e2e8f0' }}; color: {{ $b->status === 'published' ? 'var(--success-text)' : 'var(--text-light)' }};">
                                                {{ $b->status === 'published' ? 'Rilis Publik' : 'Draf' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 40px; text-align: center; color: var(--text-light);">Anda belum menulis berita apapun.</div>
                    @endif
                </div>
            </div>

            <!-- Box Tambahan: Pintasan Bantuan Konten -->
            <div class="dashboard-card" style="border-left: 4px solid var(--primary-color);">
                <div class="dashboard-card-header" style="background: var(--primary-light);">
                    <h3 class="dashboard-card-title" style="color: var(--primary-color); display: inline-flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--primary-color);"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21m0 0l-.813-5.096M9 21h3m-3 0H6m9.813-5.096A3.5 3.5 0 1118.9 13H5.1a3.5 3.5 0 113.087-2.904" /></svg>
                        Panduan Pengelolaan Konten
                    </h3>
                </div>
                <div class="dashboard-card-body">
                    <p style="font-size: 13px; line-height: 1.6; color: var(--text-secondary); margin-bottom: 12px;">
                        Sebagai <strong>Operator Konten</strong>, Anda bertanggung jawab untuk mempublikasikan artikel berita, mempublikasikan kegiatan sosial di galeri desa, serta memelihara data kependudukan desa.
                    </p>
                    <ul style="font-size: 13px; color: var(--text-secondary); padding-left: 20px; line-height: 1.8; margin-bottom: 20px;">
                        <li>Gunakan gambar unggahan berukuran wajar (maksimal 2MB) untuk menjaga performa loading website warga.</li>
                        <li>Pastikan status berita diubah menjadi <strong>Published</strong> agar dapat diakses oleh publik secara langsung.</li>
                        <li>Audit log unggahan file terus dipantau secara otomatis oleh sistem keamanan Super Admin.</li>
                    </ul>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('admin.content.posts.create') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Buat Berita
                        </a>
                        <a href="{{ route('admin.content.settings') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.297 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.214-1.28z" /></svg>
                            Profil Desa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
