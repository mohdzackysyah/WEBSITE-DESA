@extends('layouts.public')

@section('title', 'Struktur Organisasi - Pemerintah ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))

@section('styles')
<style>
    .org-chart-section {
        padding: 64px 0;
        background-color: var(--bg-main);
    }
    .org-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
        position: relative;
    }
    .org-group {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 24px;
        width: 100%;
        max-width: 1100px;
    }
    .org-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        padding: 24px 20px;
        text-align: center;
        width: 260px;
        transition: all var(--transition-fast);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }
    .org-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }
    .org-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background-color: var(--primary-light);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 16px;
        border: 2px solid var(--primary-color);
    }
    .org-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 4px;
    }
    .org-role {
        font-size: 12px;
        font-weight: 600;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .org-desc {
        font-size: 12px;
        color: var(--text-secondary);
        line-height: 1.4;
    }
    
    /* Level Separator Headers */
    .org-level-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 6px;
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
    }

    /* Connector lines logic for modern desktop view */
    @media (min-width: 993px) {
        .org-card.has-line::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            width: 2px;
            height: 40px;
            background-color: var(--border-color);
            z-index: 1;
        }
    }
</style>
@endsection

@section('content')
    <section class="org-chart-section">
        <div class="container">
            <div class="section-header" style="margin-bottom: 50px; text-align: center;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Struktur Organisasi Pemerintahan</h2>
                <p style="max-width: 600px; margin: 10px auto 0;">Bagan susunan kepemimpinan, perangkat desa, serta jajaran kepala wilayah Desa Penebal, Kecamatan Bengkalis.</p>
            </div>

            <div class="org-container">
                
                <!-- LEVEL 1: KEPALA DESA -->
                <div class="org-level-title">Kepala Desa</div>
                <div class="org-group">
                    @if($kades)
                        <div class="org-card has-line" style="border-top: 4px solid var(--primary-color); width: 280px;">
                            <div class="org-avatar" style="border-color: var(--primary-color); background-color: var(--primary-light);">
                                @if($kades->photo)
                                    <img src="{{ \Illuminate\Support\Str::contains($kades->photo, 'uploads') ? asset($kades->photo) : asset('storage/' . $kades->photo) }}" alt="{{ $kades->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-color);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                @endif
                            </div>
                            <div class="org-name">{{ $kades->name }}</div>
                            <div class="org-role">{{ $kades->position }}</div>
                            <div class="org-desc">{{ $kades->description }}</div>
                        </div>
                    @else
                        <div style="color: var(--text-light); font-size: 14px; text-align: center; width: 100%;">Belum ada data Kepala Desa.</div>
                    @endif
                </div>

                <!-- LEVEL 2: SEKRETARIS DESA -->
                <div class="org-level-title">Sekretaris Desa</div>
                <div class="org-group">
                    @if($sekdes)
                        <div class="org-card has-line" style="border-top: 4px solid #0284c7;">
                            <div class="org-avatar" style="border-color: #0284c7; background-color: #f0f9ff;">
                                @if($sekdes->photo)
                                    <img src="{{ \Illuminate\Support\Str::contains($sekdes->photo, 'uploads') ? asset($sekdes->photo) : asset('storage/' . $sekdes->photo) }}" alt="{{ $sekdes->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0284c7;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                @endif
                            </div>
                            <div class="org-name">{{ $sekdes->name }}</div>
                            <div class="org-role">{{ $sekdes->position }}</div>
                            <div class="org-desc">{{ $sekdes->description }}</div>
                        </div>
                    @else
                        <div style="color: var(--text-light); font-size: 14px; text-align: center; width: 100%;">Belum ada data Sekretaris Desa.</div>
                    @endif
                </div>

                <!-- LEVEL 3: KAUR & KASI (PERANGKAT DESA) -->
                <div class="org-level-title">Perangkat Desa (Kasi & Kaur)</div>
                <div class="org-group">
                    @forelse($perangkat as $p)
                        @php
                            $isKaur = Str::contains($p->position, 'Kaur');
                            $borderColor = $isKaur ? '#0284c7' : 'var(--primary-color)';
                            $bgColor = $isKaur ? '#f0f9ff' : 'var(--primary-light)';
                            $iconColor = $isKaur ? '#0284c7' : 'var(--primary-color)';
                        @endphp
                        <div class="org-card" style="border-top: 3px solid {{ $borderColor }};">
                            <div class="org-avatar" style="border-color: {{ $borderColor }}; background-color: {{ $bgColor }};">
                                @if($p->photo)
                                    <img src="{{ \Illuminate\Support\Str::contains($p->photo, 'uploads') ? asset($p->photo) : asset('storage/' . $p->photo) }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: {{ $iconColor }};"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                @endif
                            </div>
                            <div class="org-name">{{ $p->name }}</div>
                            <div class="org-role">{{ $p->position }}</div>
                            <div class="org-desc">{{ $p->description }}</div>
                        </div>
                    @empty
                        <div style="color: var(--text-light); font-size: 14px; text-align: center; width: 100%;">Belum ada data Perangkat Desa.</div>
                    @endforelse
                </div>

                <!-- LEVEL 4: KEPALA DUSUN -->
                <div class="org-level-title">Kepala Kewilayahan (Kepala Dusun)</div>
                <div class="org-group">
                    @forelse($kadus as $k)
                        <div class="org-card" style="border-top: 3px solid #8b5cf6;">
                            <div class="org-avatar" style="border-color: #8b5cf6; background-color: #f5f3ff;">
                                @if($k->photo)
                                    <img src="{{ \Illuminate\Support\Str::contains($k->photo, 'uploads') ? asset($k->photo) : asset('storage/' . $k->photo) }}" alt="{{ $k->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #8b5cf6;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                @endif
                            </div>
                            <div class="org-name">{{ $k->name }}</div>
                            <div class="org-role">{{ $k->position }}</div>
                            <div class="org-desc">{{ $k->description }}</div>
                        </div>
                    @empty
                        <div style="color: var(--text-light); font-size: 14px; text-align: center; width: 100%;">Belum ada data Kepala Dusun.</div>
                    @endforelse
                </div>

            </div>
        </div>
    </section>
@endsection
