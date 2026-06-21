<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Panel Operator Desa Penebal</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @yield('styles')
</head>
<body>

    <!-- Mobile Header -->
    <header class="mobile-header">
        <button id="sidebar-toggle" class="btn-toggle">
            ☰
        </button>
        <div class="mobile-header-title">
            <img src="{{ asset('images/logo-bengkalis.png') }}" alt="Logo Bengkalis" style="height: 28px; width: auto; margin-right: 8px;">
            <span>DESA PENEBAL</span>
        </div>
        <div style="width: 40px;"></div> <!-- visual spacer -->
    </header>

    <!-- Sidebar Backdrop for Mobile -->
    <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 24px;">
            <div class="sidebar-logo-group" style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo-bengkalis.png') }}" alt="Logo Bengkalis" style="height: 36px; width: auto;">
                <div class="sidebar-logo" style="font-size: 15px; font-weight: 800; background: none; -webkit-text-fill-color: var(--text-white); color: var(--text-white); display: flex; flex-direction: column; line-height: 1.1;">
                    PENEBAL <span style="font-size: 10px; color: #a7f3d0; -webkit-text-fill-color: #a7f3d0; font-weight: 500;">BENGKALIS</span>
                </div>
            </div>
            <button id="sidebar-collapse-btn" class="sidebar-collapse-btn" aria-label="Collapse Sidebar" style="background: none; border: none; color: var(--sidebar-text); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 4px; transition: var(--transition-fast);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" id="collapse-icon" style="transition: transform var(--transition-fast);"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            </button>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </span>
                    <span class="sidebar-text">Dashboard Utama</span>
                </a>
            </li>

            @if(auth()->user()->role === 'operator_pelayanan' || auth()->user()->role === 'admin')
                <li class="sidebar-item">
                    <a href="{{ route('admin.surat.index') }}" class="sidebar-link {{ request()->routeIs('admin.surat.index') || request()->routeIs('admin.surat.detail') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </span>
                        <span class="sidebar-text">Pengajuan Surat</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.surat.templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.surat.templates*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </span>
                        <span class="sidebar-text">Template Surat</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->role === 'operator_konten' || auth()->user()->role === 'admin')
                <li class="sidebar-item">
                    <a href="{{ route('admin.content.posts') }}" class="sidebar-link {{ request()->routeIs('admin.content.posts*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><line x1="8" y1="11" x2="16" y2="11"></line><line x1="8" y1="15" x2="16" y2="15"></line><line x1="8" y1="7" x2="12" y2="7"></line></svg>
                        </span>
                        <span class="sidebar-text">Kelola Berita</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.content.members.index') }}" class="sidebar-link {{ request()->routeIs('admin.content.members*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </span>
                        <span class="sidebar-text">Struktur Organisasi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.content.galleries') }}" class="sidebar-link {{ request()->routeIs('admin.content.galleries*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        </span>
                        <span class="sidebar-text">Kelola Galeri</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.content.residents') }}" class="sidebar-link {{ request()->routeIs('admin.content.residents*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </span>
                        <span class="sidebar-text">Kelola Penduduk</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.content.settings') }}" class="sidebar-link {{ request()->routeIs('admin.content.settings*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </span>
                        <span class="sidebar-text">Profil Desa</span>
                    </a>
                </li>
            @endif

            <li class="sidebar-item" style="margin-top: 30px;">
                <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </span>
                    <span class="sidebar-text">Lihat Website</span>
                </a>
            </li>
        </ul>

        <!-- Sidebar User Footer -->
        <div class="sidebar-user">
            <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
            <span class="sidebar-user-role">
                {{ auth()->user()->role === 'admin' ? 'Administrator' : (auth()->user()->role === 'operator_pelayanan' ? 'Operator Pelayanan' : 'Operator Konten') }}
            </span>
            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                @csrf
                <button type="submit" class="btn-logout" style="background: none; border: none; cursor: pointer; text-align: left; padding: 0; display: inline-flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Display Session Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Sidebar Toggle
            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    backdrop.classList.toggle('active');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    backdrop.classList.remove('active');
                });
            }

            // Desktop Sidebar Collapse/Expand
            const collapseBtn = document.getElementById('sidebar-collapse-btn');
            const collapseIcon = document.getElementById('collapse-icon');
            const body = document.body;
 
            // Check localStorage state
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                body.classList.add('sidebar-collapsed');
                if (collapseIcon) collapseIcon.style.transform = 'rotate(180deg)';
            }
 
            if (collapseBtn) {
                collapseBtn.addEventListener('click', function() {
                    body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = body.classList.contains('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', isCollapsed);
                    if (collapseIcon) {
                        collapseIcon.style.transform = isCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
