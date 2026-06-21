@extends('layouts.admin')

@section('title', 'Daftar Pengajuan Surat')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>Manajemen Layanan Surat</h1>
            <p>Kelola verifikasi, proses draf surat, cetak PDF, dan validasi data pengajuan warga desa.</p>
        </div>
    </div>

    <!-- Stats Cards Summary -->
    @php
        $stats = [
            'total' => \App\Models\SuratRequest::count(),
            'menunggu' => \App\Models\SuratRequest::where('status', 'menunggu_verifikasi')->count(),
            'proses' => \App\Models\SuratRequest::where('status', 'diproses')->count(),
            'selesai' => \App\Models\SuratRequest::where('status', 'selesai')->count(),
        ];
    @endphp
    <div class="stats-grid">
        <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
            <h3>Total Pengajuan</h3>
            <div class="stat-card-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #d97706;">
            <h3>Menunggu Verifikasi</h3>
            <div class="stat-card-value" style="color: #d97706;">{{ $stats['menunggu'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #0284c7;">
            <h3>Sedang Diproses</h3>
            <div class="stat-card-value" style="color: #0284c7;">{{ $stats['proses'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <h3>Surat Selesai</h3>
            <div class="stat-card-value" style="color: #059669;">{{ $stats['selesai'] }}</div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h3 style="font-size: 16px; font-weight: 700;">Daftar Permohonan Masuk</h3>
            
            <!-- Filters -->
            <form action="{{ route('admin.surat.index') }}" method="GET" class="table-filter-form">
                <select name="status" class="form-control" style="width: 180px; padding: 6px 12px; font-size: 13px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, NIK, kode..." 
                    class="form-control"
                    style="width: 220px; padding: 6px 12px; font-size: 13px;"
                >
                <button type="submit" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" /></svg>
                    Filter
                </button>
                @if(request('status') || request('search'))
                    <a href="{{ route('admin.surat.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nomor Pengajuan</th>
                        <th>NIK</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: var(--primary-color);">{{ $req->nomor_pengajuan }}</td>
                            <td>{{ $req->nik }}</td>
                            <td style="font-weight: 600;">{{ $req->nama_lengkap }}</td>
                            <td>
                                <span style="font-size: 13px; font-weight: 500;">
                                    {{ match($req->jenis_surat) { 'domisili' => 'Domisili', 'sktm' => 'SKTM', 'pindah' => 'Pindah', default => $req->jenis_surat } }}
                                </span>
                            </td>
                            <td>{{ $req->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $req->status }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.surat.detail', $req->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                                    Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-light); padding: 40px;">
                                Tidak ada pengajuan surat yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->total() > $requests->perPage())
            <div style="padding: 20px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                {{ $requests->appends(request()->input())->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>
@endsection
