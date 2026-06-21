<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator - {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }} Digital</title>
    <!-- Embed app.css for premium styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: radial-gradient(circle at 10% 20%, rgba(37,99,235,0.05) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(16,185,129,0.03) 0%, transparent 40%);
            background-color: var(--bg-main);
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 40px;
            background: var(--bg-card);
            backdrop-filter: blur(8px);
            border: 1px solid var(--border-glass);
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-md);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 12px;">
                <img src="{{ asset('images/logo-bengkalis.png') }}" alt="Logo Bengkalis" style="height: 48px; width: auto;">
                <div style="text-align: left; line-height: 1.2;">
                    <div style="font-family: var(--font-heading); font-weight: 800; font-size: 18px; color: var(--primary-color);">DESA PENEBAL</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--text-light);">Kabupaten Bengkalis</div>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Panel Operator & Administrator Portal Desa</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 24px; padding: 12px; font-size: 13px;">
                @foreach ($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email Operator</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    class="form-control" 
                    placeholder="operator@desa.go.id" 
                    required 
                    autofocus
                >
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label for="password">Kata Sandi</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control" 
                    placeholder="••••••••" 
                    required
                >
            </div>

            <div class="form-group" style="margin-top: 16px; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 13px; cursor: pointer;">
                    <input type="checkbox" name="remember">
                    <span style="color: var(--text-secondary);">Ingat Saya di Perangkat Ini</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                Masuk Sistem Operator
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 24px; font-size: 12px; color: var(--text-light);">
            <a href="{{ route('home') }}" style="text-decoration: underline; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Halaman Utama Warga
            </a>
        </div>
    </div>

</body>
</html>
