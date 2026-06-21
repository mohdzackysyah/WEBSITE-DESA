@extends('layouts.public')

@section('title', $post->title . ' - Desa Makmur')

@section('content')
    <section class="section" style="padding-top: 48px;">
        <div class="container" style="max-width: 840px;">
            <!-- Back Button -->
            <a href="{{ route('berita') }}" style="color: var(--text-secondary); display: inline-flex; align-items: center; gap: 8px; margin-bottom: 24px; font-weight: 500;">
                ⬅ Kembali ke Berita
            </a>

            <!-- Article Header -->
            <header style="margin-bottom: 32px;">
                <span style="font-size: 13px; color: var(--primary-color); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">Kabar Desa</span>
                <h1 style="font-size: 38px; line-height: 1.25; color: var(--secondary-color); margin-bottom: 16px;">{{ $post->title }}</h1>
                <div style="font-size: 14px; color: var(--text-secondary);">
                    Dipublikasikan pada <strong>{{ $post->created_at->translatedFormat('d F Y H:i') }} WIB</strong>
                </div>
            </header>

            <!-- Article Image -->
            @if($post->image)
                <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 40px; box-shadow: var(--shadow-md);">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" style="width: 100%; height: auto; max-height: 480px; object-fit: cover; display: block;">
                </div>
            @endif

            <!-- Article Body -->
            <article class="card wysiwyg-content" style="font-size: 16px; color: var(--text-secondary); line-height: 1.8; padding: 40px;">
                {!! $post->content !!}
            </article>

            <!-- Footer Share / CTA -->
            <div style="margin-top: 48px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <span style="font-size: 14px; color: var(--text-light);">Bagikan berita ini ke kerabat Anda untuk menyebarkan informasi positif.</span>
                <div style="display: flex; gap: 12px;">
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . url()->current()) }}" target="_blank" class="btn btn-secondary btn-sm" style="background-color: #25d366; color: white; border: none;">
                        Share WA
                    </a>
                    <a href="{{ route('berita') }}" class="btn btn-secondary btn-sm">Semua Kabar</a>
                </div>
            </div>
        </div>
    </section>
@endsection
