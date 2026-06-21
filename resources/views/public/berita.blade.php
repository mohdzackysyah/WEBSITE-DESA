@extends('layouts.public')

@section('title', 'Berita & Kegiatan ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal') . ' - Portal Resmi')

@section('content')
    <section class="section" style="padding-top: 48px; min-height: 70vh;">
        <div class="container">
            <!-- Header & Search Bar -->
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
                <div class="section-header" style="margin: 0; text-align: left; max-width: 600px;">
                    <h2 style="font-size: 32px; color: var(--secondary-color);">Berita & Kegiatan</h2>
                    <p>Ikuti perkembangan terbaru mengenai dinamika pembangunan, agenda kemasyarakatan, dan pengumuman resmi {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }}.</p>
                </div>
                
                <form action="{{ route('berita') }}" method="GET" style="display: flex; gap: 8px; width: 100%; max-width: 380px;">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari berita atau pengumuman..." 
                        class="form-control"
                        style="padding: 10px 16px;"
                    >
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Cari</button>
                </form>
            </div>

            @if(request('search'))
                <div style="margin-bottom: 24px; color: var(--text-secondary); font-size: 15px;">
                    Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong> 
                    <a href="{{ route('berita') }}" style="color: var(--primary-color); margin-left: 8px; text-decoration: underline;">(Hapus Pencarian)</a>
                </div>
            @endif

            <!-- News Grid -->
            <div class="news-grid">
                @forelse($posts as $post)
                    <div class="news-card">
                        <img src="{{ $post->image ?? 'https://images.unsplash.com/photo-1596436889106-be35e843f974?q=80&w=800' }}" alt="{{ $post->title }}" class="news-card-image">
                        <div class="news-card-content">
                            <span class="news-card-date">{{ $post->created_at->translatedFormat('d F Y') }}</span>
                            <h3 class="news-card-title"><a href="{{ route('berita.detail', $post->slug) }}">{{ $post->title }}</a></h3>
                            <p class="news-card-excerpt">{!! strip_tags($post->content) !!}</p>
                            <a href="{{ route('berita.detail', $post->slug) }}" class="news-card-more">Baca Selengkapnya ➔</a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 3; text-align: center; color: var(--text-light); padding: 80px 20px;">
                        <span style="font-size: 48px; display: block; margin-bottom: 16px;">🔍</span>
                        Belum ada berita yang ditemukan. Coba gunakan kata kunci lainnya.
                    </div>
                @endforelse
            </div>

            <!-- Pagination links -->
            @if($posts->total() > $posts->perPage())
                <div style="margin-top: 48px; display: flex; justify-content: center;">
                    {{ $posts->appends(request()->input())->links('vendor.pagination.simple-default') }}
                </div>
            @endif
        </div>
    </section>
@endsection
