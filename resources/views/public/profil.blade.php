@extends('layouts.public')

@section('title', 'Profil ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal') . ' - Portal Resmi')

@section('content')
    <section class="section" style="padding-top: 48px;">
        <div class="container">
            <div class="section-header" style="margin-bottom: 40px; text-align: left; max-width: 100%;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Profil Desa</h2>
                <p>Mengenal sejarah panjang, visi, misi, jajaran kepemimpinan, dan informasi wilayah {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }}.</p>
            </div>

            <div class="profile-grid">
                <!-- Sidebar Info -->
                <div class="profile-sidebar">
                    @if($kades && $kades->photo)
                        <img src="{{ \Illuminate\Support\Str::contains($kades->photo, 'uploads') ? asset($kades->photo) : asset('storage/' . $kades->photo) }}" alt="{{ $kades->name }}" class="profile-avatar-img">
                    @else
                        <div class="profile-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                    @endif
                    <h3>{{ $nama_kepala ?? 'M. Sani' }}</h3>
                    <span style="color: var(--primary-color); font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 4px;">Kepala Desa {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }}</span>
                    
                    <div class="profile-info-mini">
                        <div class="profile-info-row">
                            <strong>Jam Pelayanan Kantor:</strong>
                            <p style="white-space: pre-line; font-size: 13px; color: var(--text-secondary); margin-top: 4px;">{{ $jam_pelayanan }}</p>
                        </div>
                        <div class="profile-info-row">
                            <strong>Telepon Kantor:</strong>
                            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">{{ $kontak_telepon }}</p>
                        </div>
                        <div class="profile-info-row">
                            <strong>Email Desa:</strong>
                            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">{{ $kontak_email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="profile-content">
                    <!-- Sambutan -->
                    <div class="card">
                        <h3 style="font-size: 22px; margin-bottom: 16px; color: var(--primary-color);">Kata Sambutan</h3>
                        <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.8; font-style: italic;">
                            "{{ $sambutan }}"
                        </p>
                    </div>

                    <!-- Sejarah -->
                    <div class="card">
                        <h3 style="font-size: 22px; margin-bottom: 16px; color: var(--primary-color);">Sejarah Desa</h3>
                        <div class="wysiwyg-content" style="font-size: 15px; color: var(--text-secondary);">
                            <p>{{ $sejarah }}</p>
                        </div>
                    </div>

                    <!-- Visi & Misi -->
                    <div class="card">
                        <h3 style="font-size: 22px; margin-bottom: 16px; color: var(--primary-color);">Visi & Misi</h3>
                        <div style="margin-bottom: 20px;">
                            <strong style="color: var(--secondary-color); font-size: 16px; display: block; margin-bottom: 8px;">VISI:</strong>
                            <p style="font-size: 15px; color: var(--text-secondary); font-weight: 500; font-style: italic;">"{{ $visi }}"</p>
                        </div>
                        <div>
                            <strong style="color: var(--secondary-color); font-size: 16px; display: block; margin-bottom: 8px;">MISI:</strong>
                            <p style="white-space: pre-line; font-size: 15px; color: var(--text-secondary); line-height: 1.8;">{{ $misi }}</p>
                        </div>
                    </div>

                    <!-- Peta & Kontak -->
                    <div class="card">
                        <h3 style="font-size: 22px; margin-bottom: 16px; color: var(--primary-color);">Lokasi & Kontak Kantor Desa</h3>
                        <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 20px;">
                            <strong>Alamat Lengkap:</strong> {{ $alamat }}
                        </p>
                        <div style="border-radius: var(--radius-sm); overflow: hidden; height: 320px; border: 1px solid var(--border-color);">
                            <iframe 
                                src="{{ $peta_lokasi }}" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
