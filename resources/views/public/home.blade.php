@extends('layouts.public')

@section('title', 'Selamat Datang di Portal Resmi ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))

@section('content')
    <!-- Hero Banner Section -->
    <section class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url('{{ asset('images/hero-bengkalis.jpg') }}') no-repeat center center / cover; min-height: 520px; display: flex; align-items: center; justify-content: center; text-align: center; color: var(--text-white); padding: 80px 24px; position: relative;">
        <div style="max-width: 800px; margin: 0 auto; width: 100%;">
            <span style="font-size: clamp(24px, 4vw, 36px); font-weight: 500; font-family: var(--font-heading); color: #ffffff; display: block; margin-bottom: 8px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">Selamat Datang di</span>
            <h1 style="font-size: clamp(38px, 6vw, 68px); font-weight: 800; font-family: var(--font-heading); color: #ffffff; line-height: 1.15; margin-bottom: 20px; letter-spacing: -0.5px; text-shadow: 0 4px 16px rgba(0,0,0,0.3); -webkit-text-fill-color: #ffffff; background: none; -webkit-background-clip: unset;">Desa Penebal</h1>
            <p style="font-size: clamp(14px, 2vw, 17px); color: rgba(255, 255, 255, 0.95); margin-bottom: 32px; line-height: 1.6; text-shadow: 0 1px 3px rgba(0,0,0,0.2); max-width: 620px; margin-left: auto; margin-right: auto;">Portal resmi pelayanan dan informasi masyarakat Desa Penebal</p>
            <div class="hero-btns" style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('layanan.index') }}" class="btn btn-primary" style="background-color: #15803d; color: #ffffff; box-shadow: 0 4px 14px rgba(21, 128, 61, 0.35); border: none; font-weight: 600; padding: 12px 28px; border-radius: var(--radius-md); font-size: 15px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                    Layanan Surat Online <span style="font-size: 18px; line-height: 1;">→</span>
                </a>
                <a href="{{ route('profil.tentang') }}" class="btn btn-secondary" style="background-color: #ffffff; color: #1f2937; border: none; font-weight: 600; padding: 12px 28px; border-radius: var(--radius-md); font-size: 15px; text-decoration: none; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                    Tentang Desa
                </a>
            </div>
        </div>
    </section>

    <!-- Quick Stat Infographics -->
    <div class="container">
        <section class="quick-stats">
            <div class="stat-item">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalPenduduk }}</h3>
                    <p>Total Penduduk</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalKK }}</h3>
                    <p>Kepala Keluarga</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalUMKM }}</h3>
                    <p>Unit UMKM Lokal</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Sambutan Kepala Desa -->
    <section class="section">
        <div class="container">
            <div class="kades-card">
                <div class="kades-profile">
                    <div class="kades-avatar">
                        @if($kades && $kades->photo)
                            <img src="{{ \Illuminate\Support\Str::contains($kades->photo, 'uploads') ? asset($kades->photo) : asset('storage/' . $kades->photo) }}" alt="{{ $kades->name }}">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        @endif
                    </div>
                    <h3 class="kades-name">{{ $kades ? $kades->name : \App\Models\Setting::get('nama_kepala', 'M. Sani') }}</h3>
                    <span class="kades-title">Kepala Desa {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }}</span>
                </div>
                <div>
                    <h2 class="kades-message-title">Sambutan Kepala Desa</h2>
                    <p class="kades-message-text">
                        "{{ \App\Models\Setting::get('sambutan') ?: \App\Models\Setting::get('sambutan_kepala') }}"
                    </p>
                    <a href="{{ route('profil') }}" class="btn btn-secondary btn-sm">Baca Visi & Misi</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview Section -->
    <section class="section" style="background-color: #f1f5f9;">
        <div class="container">
            <div class="section-header">
                <h2>Layanan Surat Mandiri</h2>
                <p>Ajukan permohonan surat administrasi desa Anda secara online secara aman tanpa perlu melakukan login.</p>
            </div>
            
            <div class="features-grid">
                <!-- Service 1 -->
                <div class="card feature-card">
                    <h3 style="margin-top: 8px;">Keterangan Domisili</h3>
                    <p style="margin-bottom: 20px;">Diberikan untuk warga desa yang memerlukan surat resmi keterangan tempat tinggal terdaftar.</p>
                    <a href="{{ route('layanan.form', 'domisili') }}" class="btn btn-primary btn-sm">Buat Surat</a>
                </div>
                <!-- Service 2 -->
                <div class="card feature-card">
                    <h3 style="margin-top: 8px;">Surat Keterangan Tidak Mampu</h3>
                    <p style="margin-bottom: 20px;">SKTM diterbitkan untuk syarat keringanan biaya sekolah, fasilitas kesehatan, atau bantuan sosial.</p>
                    <a href="{{ route('layanan.form', 'sktm') }}" class="btn btn-primary btn-sm">Buat Surat</a>
                </div>
                <!-- Service 3 -->
                <div class="card feature-card">
                    <h3 style="margin-top: 8px;">Keterangan Pindah</h3>
                    <p style="margin-bottom: 20px;">Mengurus surat pindah penduduk dari {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }} menuju alamat tujuan di luar daerah secara resmi.</p>
                    <a href="{{ route('layanan.form', 'pindah') }}" class="btn btn-primary btn-sm">Buat Surat</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Kabar & Berita Desa</h2>
                <p>Ikuti perkembangan terbaru mengenai kegiatan kemasyarakatan, pembangunan infrastruktur, dan pengumuman resmi desa.</p>
            </div>

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
                    <div style="grid-column: span 3; text-align: center; color: var(--text-light); padding: 40px;">
                        Belum ada berita yang diterbitkan.
                    </div>
                @endforelse
            </div>
            
            @if($posts->count() > 0)
                <div style="text-align: center; margin-top: 48px;">
                    <a href="{{ route('berita') }}" class="btn btn-secondary">Lihat Semua Berita</a>
                </div>
            @endif
        </div>
    </section>
@endsection
