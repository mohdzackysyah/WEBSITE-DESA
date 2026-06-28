<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website Resmi {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }} - Portal informasi profil, berita, galeri, statistik, dan pelayanan pengajuan surat mandiri online.">
    <title>@yield('title', 'Website Resmi ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Chart.js for Statistics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('styles')
</head>
<body>

    <!-- Navigation Header -->
    <header class="navbar">
        <div class="container navbar-container">
            <a href="{{ route('home') }}" class="navbar-logo" style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo-bengkalis.png') }}" alt="Logo Bengkalis" style="height: 44px; width: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                <div style="display: flex; flex-direction: column; line-height: 1.2;">
                    <span style="font-size: 16px; font-weight: 800; letter-spacing: 0.5px; color: var(--text-white); font-family: var(--font-heading);">DESA PENEBAL</span>
                    <span style="font-size: 10px; font-weight: 500; color: #a7f3d0; opacity: 0.85;">Kec. Bengkalis, Kab. Bengkalis</span>
                </div>
            </a>
            
            <!-- Toggle Button for Mobile Navigation -->
            <button id="navbar-toggle" class="navbar-toggle" aria-label="Menu Utama">
                ☰
            </button>
            
            <ul class="navbar-menu">
                <li>
                    <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>
                
                <!-- Dropdown Profil Desa -->
                <li class="navbar-dropdown">
                    <a href="#" class="navbar-link {{ request()->routeIs('profil*') || request()->routeIs('statistik') ? 'active' : '' }}">
                        Profil Desa <span class="arrow">▾</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('profil.tentang') }}" class="{{ request()->routeIs('profil.tentang') ? 'active' : '' }}">
                                Tentang Desa
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profil.struktur') }}" class="{{ request()->routeIs('profil.struktur') ? 'active' : '' }}">
                                Struktur Organisasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('statistik') }}" class="{{ request()->routeIs('statistik') ? 'active' : '' }}">
                                Data Statistik
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Dropdown Berita & Kegiatan -->
                <li class="navbar-dropdown">
                    <a href="#" class="navbar-link {{ request()->routeIs('berita*') || request()->routeIs('galeri*') ? 'active' : '' }}">
                        Berita & Kegiatan <span class="arrow">▾</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('berita') }}" class="{{ request()->routeIs('berita*') ? 'active' : '' }}">
                                Berita
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri*') ? 'active' : '' }}">
                                Galeri Foto
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Dropdown Pelayanan -->
                <li class="navbar-dropdown">
                    <a href="#" class="navbar-link {{ request()->routeIs('layanan*') ? 'active' : '' }}">
                        Pelayanan <span class="arrow">▾</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.index') || request()->routeIs('layanan.form') ? 'active' : '' }}">
                                Pengajuan Surat
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('layanan.lacak') }}" class="{{ request()->routeIs('layanan.lacak') ? 'active' : '' }}">
                                Lacak Surat
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Kontak Page -->
                <li>
                    <a href="{{ route('kontak') }}" class="navbar-link {{ request()->routeIs('kontak') ? 'active' : '' }}">
                        Kontak
                    </a>
                </li>
            </ul>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; margin-bottom: 16px;">
                    <img src="{{ asset('images/logo-bengkalis.png') }}" alt="Logo Bengkalis" style="height: 44px; width: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));">
                    <div style="display: flex; flex-direction: column; line-height: 1.2; text-align: left;">
                        <span style="font-size: 16px; font-weight: 800; letter-spacing: 0.5px; color: var(--text-white); font-family: var(--font-heading);">DESA PENEBAL</span>
                        <span style="font-size: 10px; font-weight: 500; color: #a7f3d0; opacity: 0.85;">Kec. Bengkalis, Kab. Bengkalis</span>
                    </div>
                </a>
                <p>Mewujudkan tata kelola desa yang maju, transparan, dan mandiri melalui inovasi pelayanan publik digital terpadu.</p>
            </div>
            <div>
                <h4 class="footer-title">Menu Utama</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                    <li><a href="{{ route('profil') }}" class="footer-link">Profil Desa</a></li>
                    <li><a href="{{ route('berita') }}" class="footer-link">Berita & Kegiatan</a></li>
                    <li><a href="{{ route('statistik') }}" class="footer-link">Statistik Penduduk</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Layanan Surat</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('layanan.form', 'domisili') }}" class="footer-link">Keterangan Domisili</a></li>
                    <li><a href="{{ route('layanan.form', 'sktm') }}" class="footer-link">Keterangan Tidak Mampu</a></li>
                    <li><a href="{{ route('layanan.form', 'pindah') }}" class="footer-link">Keterangan Pindah</a></li>
                    <li><a href="{{ route('layanan.lacak') }}" class="footer-link">Lacak Status Surat</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Hubungi Kami</h4>
                <ul class="footer-links" style="font-size: 13px; line-height: 1.6;">
                    <li style="margin-bottom: 10px;">
                        <strong>Alamat:</strong><br>
                        {{ \App\Models\Setting::get('alamat') }}
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Telepon:</strong><br>
                        {{ \App\Models\Setting::get('kontak_telepon') }}
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Email:</strong><br>
                        {{ \App\Models\Setting::get('kontak_email') }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>&copy; {{ date('Y') }} Pemerintah {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }}.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('navbar-toggle');
            const navMenu = document.querySelector('.navbar-menu');
            const dropdowns = document.querySelectorAll('.navbar-dropdown');
            
            if (navToggle && navMenu) {
                navToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('show');
                    const isShown = navMenu.classList.contains('show');
                    navToggle.textContent = isShown ? '✕' : '☰';
                    if (!isShown) {
                        dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
                    }
                });
            }

            // Toggle dropdowns on mobile click
            dropdowns.forEach(dropdown => {
                const link = dropdown.querySelector('.navbar-link');
                if (link) {
                    link.addEventListener('click', function(e) {
                        if (window.innerWidth <= 992) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Close other dropdowns
                            dropdowns.forEach(other => {
                                if (other !== dropdown) {
                                    other.classList.remove('active');
                                }
                            });
                            
                            dropdown.classList.toggle('active');
                        }
                    });
                }
            });
            
            // Close mobile menu if clicked outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                        navMenu.classList.remove('show');
                        navToggle.textContent = '☰';
                        dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
                    }
                }
            });
        });
    </script>

    @include('partials.custom_confirm')
    @yield('scripts')
</body>
</html>
