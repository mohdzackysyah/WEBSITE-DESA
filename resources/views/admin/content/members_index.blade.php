@extends('layouts.admin')

@section('title', 'Kelola Struktur Organisasi')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>Kelola Struktur Organisasi</h1>
            <p>Tambah, ubah, atau hapus susunan kepemimpinan dan perangkat Desa Penebal agar tampil dinamis di halaman depan.</p>
        </div>
        <div>
            <a href="{{ route('admin.content.members.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Anggota Baru
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h3 style="font-size: 16px; font-weight: 700;">Daftar Kepengurusan Desa</h3>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Jabatan / Posisi</th>
                        <th>Tingkatan Struktur</th>
                        <th style="width: 100px; text-align: center;">Urutan Tampil</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr>
                            <td>
                                @if($m->photo)
                                    <img src="{{ asset($m->photo) }}" alt="Foto" class="news-thumb" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 600;">
                                        {{ substr($m->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size: 15px; display: block; color: var(--text-primary);">{{ $m->name }}</strong>
                                <span style="font-size: 11px; color: var(--text-light); display: block; margin-top: 2px;">Terdaftar: {{ $m->created_at->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: var(--primary-color);">{{ $m->position }}</span>
                            </td>
                            <td>
                                <span class="badge" style="
                                    @if($m->level == 1) background-color: #fef3c7; color: #d97706;
                                    @elseif($m->level == 2) background-color: #e0f2fe; color: #0369a1;
                                    @elseif($m->level == 3) background-color: #d1fae5; color: #065f46;
                                    @else background-color: #f3e8ff; color: #7c3aed; @endif
                                ">
                                    {{ match($m->level) { 1 => 'Kepala Desa', 2 => 'Sekretaris Desa', 3 => 'Kasi & Kaur', 4 => 'Kepala Dusun', default => $m->level } }}
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: var(--text-secondary);">
                                {{ $m->sort_order }}
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.content.members.edit', $m->id) }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.content.members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota struktur organisasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-light); padding: 40px;">
                                Belum ada anggota struktur organisasi yang ditambahkan. Silakan tambah anggota baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->total() > $members->perPage())
            <div style="padding: 20px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                {{ $members->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>
@endsection
