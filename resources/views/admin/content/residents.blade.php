@extends('layouts.admin')

@section('title', 'Kelola Penduduk & Statistik')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>Kelola Data Penduduk & Statistik</h1>
            <p>Kelola data kependudukan desa untuk memperbarui data statistik dan memvalidasi NIK warga secara dinamis.</p>
        </div>
        <div>
            <a href="{{ route('admin.content.residents.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Penduduk Baru
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
            <h3>Total Penduduk</h3>
            <div class="stat-card-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #0284c7;">
            <h3>Laki-laki</h3>
            <div class="stat-card-value" style="color: #0284c7;">{{ $stats['laki'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #db2777;">
            <h3>Perempuan</h3>
            <div class="stat-card-value" style="color: #db2777;">{{ $stats['perempuan'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <h3>Pelaku UMKM</h3>
            <div class="stat-card-value" style="color: #059669;">{{ $stats['umkm'] }}</div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header" style="flex-wrap: wrap; gap: 16px;">
            <h3 style="font-size: 16px; font-weight: 700;">Daftar Penduduk Desa</h3>
            
            <!-- Filters & Search Form -->
            <form action="{{ route('admin.content.residents') }}" method="GET" class="table-filter-form" style="margin-left: auto;">
                <select name="rw" class="form-control" style="width: 140px; padding: 6px 12px; font-size: 13px;" onchange="this.form.submit()">
                    <option value="">Semua RW</option>
                    @foreach($rwList as $rw)
                        <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>RW {{ $rw }}</option>
                    @endforeach
                </select>
                <div style="position: relative; display: flex; gap: 8px;">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari NIK, Nama, Alamat..." 
                        class="form-control" 
                        style="width: 240px; padding: 6px 12px; font-size: 13px;"
                     >
                    @if(request()->filled('search') || request()->filled('rw'))
                        <a href="{{ route('admin.content.residents') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px; display: inline-flex; align-items: center; justify-content: center; height: 34px; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                            Reset
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary" style="padding: 6px 16px; font-size: 13px; height: 34px;">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th style="width: 80px; text-align: center;">Gender</th>
                        <th>Pekerjaan</th>
                        <th>RT / RW</th>
                        <th>Bansos</th>
                        <th>UMKM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $res)
                        <tr>
                            <td style="font-family: monospace; font-weight: 600; color: var(--text-secondary);">
                                {{ $res->nik }}
                            </td>
                            <td>
                                <strong style="font-size: 15px; color: var(--text-primary);">{{ $res->nama_lengkap }}</strong>
                                <span style="font-size: 12px; color: var(--text-light); display: block; margin-top: 2px;">
                                    {{ $res->tempat_lahir }}, {{ \Carbon\Carbon::parse($res->tanggal_lahir)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background-color: {{ $res->jenis_kelamin === 'L' ? '#e0f2fe' : '#fce7f3' }}; color: {{ $res->jenis_kelamin === 'L' ? '#0369a1' : '#be185d' }};">
                                    {{ $res->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                </span>
                            </td>
                            <td>{{ $res->pekerjaan }}</td>
                            <td>RT {{ $res->rt }} / RW {{ $res->rw }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $res->bantuan_sosial !== 'Tidak Ada' ? '#d1fae5' : '#f1f5f9' }}; color: {{ $res->bantuan_sosial !== 'Tidak Ada' ? '#065f46' : '#64748b' }};">
                                    {{ $res->bantuan_sosial }}
                                </span>
                            </td>
                            <td>
                                @if($res->is_umkm)
                                    <span class="badge badge-selesai" title="{{ $res->nama_umkm }}">
                                        Yes
                                    </span>
                                @else
                                    <span class="badge" style="background-color: #f1f5f9; color: #64748b;">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.content.residents.edit', $res->nik) }}" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 4px 8px; display: inline-flex; align-items: center; gap: 6px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.content.residents.destroy', $res->nik) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penduduk {{ $res->nama_lengkap }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size: 12px; padding: 4px 8px; display: inline-flex; align-items: center; gap: 6px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-light); padding: 40px;">
                                Tidak ada data penduduk yang cocok dengan kriteria pencarian/filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($residents->total() > $residents->perPage())
            <div style="padding: 20px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                {{ $residents->appends(request()->query())->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>
@endsection
