@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>Kelola Berita & Kabar Desa</h1>
            <p>Tambah, edit, atau hapus konten berita dan pengumuman untuk halaman warga.</p>
        </div>
        <div>
            <a href="{{ route('admin.content.posts.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Berita Baru
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h3 style="font-size: 16px; font-weight: 700;">Daftar Konten Berita</h3>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Gambar</th>
                        <th>Judul Berita</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <img src="{{ $post->image ?? 'https://images.unsplash.com/photo-1596436889106-be35e843f974?q=80&w=800' }}" alt="Thumb" class="news-thumb">
                            </td>
                            <td>
                                <strong style="font-size: 15px; display: block; margin-bottom: 4px;">{{ $post->title }}</strong>
                                <span style="font-size: 12px; color: var(--text-light);">{{ Str::words(strip_tags($post->content), 12) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $post->status === 'published' ? 'selesai' : 'menunggu_verifikasi' }}">
                                    {{ $post->status === 'published' ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $post->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.content.posts.edit', $post->id) }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.content.posts.destroy', $post->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus berita ini?" data-confirm-title="Hapus Berita" data-confirm-type="danger">
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
                            <td colspan="5" style="text-align: center; color: var(--text-light); padding: 40px;">
                                Belum ada berita yang ditulis. Silakan tambah berita baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->total() > $posts->perPage())
            <div style="padding: 20px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                {{ $posts->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>
@endsection
