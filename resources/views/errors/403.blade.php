<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <!-- Outfit & Plus Jakarta Sans fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <style>
        :root {
            --primary-color: #0b663e;
            --primary-hover: #074e2f;
            --primary-light: #f0fdf4;
            --secondary-color: #0b2216;
            --accent-color: #10b981;
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --danger-light: #fef2f2;
            
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-light: #94a3b8;
            
            --bg-main: #f8fafc;
            --bg-card: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(255, 255, 255, 0.5);
            
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-full: 9999px;
            
            --shadow-lg: 0 20px 40px -15px rgba(15, 23, 42, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at 10% 20%, rgba(254, 242, 242, 0.4) 0%, rgba(248, 250, 252, 1) 90%);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
            position: relative;
        }

        /* Decorative background blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background-color: rgba(239, 68, 68, 0.1);
        }

        .blob-2 {
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background-color: rgba(11, 102, 62, 0.15);
        }

        .error-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 560px;
            padding: 48px 32px;
            text-align: center;
            position: relative;
            transform: translateY(0);
            animation: floatCard 6s ease-in-out infinite;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .svg-illustration {
            width: 220px;
            height: 220px;
            margin: 0 auto 24px auto;
        }

        /* SVG animations */
        .shield-group {
            animation: shieldPulse 3s ease-in-out infinite alternate;
            transform-origin: 110px 100px;
        }

        @keyframes shieldPulse {
            0% { transform: scale(0.97); }
            100% { transform: scale(1.03); }
        }

        .lock-shackle {
            animation: lockShackleMovement 4s ease-in-out infinite;
            transform-origin: 110px 105px;
        }

        @keyframes lockShackleMovement {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        .alert-ring {
            animation: alertRingPulse 2s ease-in-out infinite;
            transform-origin: 110px 100px;
        }

        @keyframes alertRingPulse {
            0% { r: 55px; opacity: 0.8; stroke-width: 1; }
            50% { r: 70px; opacity: 0; stroke-width: 2; }
            100% { r: 70px; opacity: 0; stroke-width: 0; }
        }

        .error-code {
            font-family: 'Outfit', sans-serif;
            font-size: 80px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--danger-color), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 12px;
            letter-spacing: -2px;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 12px;
        }

        p {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 32px;
            padding: 0 16px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: center;
            align-items: center;
        }

        @media (min-width: 480px) {
            .button-group {
                flex-direction: row;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            width: 100%;
        }

        @media (min-width: 480px) {
            .btn {
                width: auto;
            }
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: #ffffff;
            border: 1px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(11, 102, 62, 0.15);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(11, 102, 62, 0.25);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #f1f5f9;
            transform: translateY(-2px);
        }

        .footer-text {
            margin-top: 40px;
            font-size: 12px;
            color: var(--text-light);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <!-- Background Decoration -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="error-container">
        <!-- SVG Illustration -->
        <div class="svg-illustration">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 220 220" fill="none">
                <!-- Base Shadow -->
                <ellipse cx="110" cy="185" rx="60" ry="10" fill="#E2E8F0" />
                
                <!-- Glowing Alert Pulse -->
                <circle class="alert-ring" cx="110" cy="100" r="55" fill="none" stroke="#ef4444" stroke-width="2" />
                
                <!-- Shield Base -->
                <g class="shield-group">
                    <!-- Outer Shield -->
                    <path d="M110 40L165 60V110C165 145 140 170 110 180C80 170 55 145 55 110V60L110 40Z" fill="#F8FAFC" stroke="#ef4444" stroke-width="4" stroke-linejoin="round" />
                    <!-- Inner Shield Decoration -->
                    <path d="M110 48L155 64V105C155 134 135 155 110 164C85 155 65 134 65 105V64L110 48Z" fill="#ef4444" fill-opacity="0.05" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="3 3" />
                    
                    <!-- Padlock Body -->
                    <rect x="90" y="105" width="40" height="30" rx="6" fill="#1E293B" />
                    
                    <!-- Padlock Shackle -->
                    <path class="lock-shackle" d="M97 105V95C97 87.8203 102.82 82 110 82C117.18 82 123 87.8203 123 95V105" stroke="#1E293B" stroke-width="5" stroke-linecap="round" />
                    
                    <!-- Keyhole -->
                    <circle cx="110" cy="117" r="3.5" fill="#F8FAFC" />
                    <path d="M110 120.5V127" stroke="#F8FAFC" stroke-width="2" stroke-linecap="round" />
                </g>
            </svg>
        </div>

        <div class="error-code">403</div>
        <h1>Akses Ditolak (Forbidden)</h1>
        <p>Maaf, Anda tidak memiliki hak akses atau izin yang diperlukan untuk melihat halaman ini. Silakan kembali ke dashboard atau hubungi administrator desa.</p>

        <div class="button-group">
            <a href="{{ auth()->check() ? route('admin.dashboard') : '/' }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                Kembali ke {{ auth()->check() ? 'Dashboard' : 'Beranda' }}
            </a>
            <button onclick="window.history.back()" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Halaman Sebelumnya
            </button>
        </div>

        <div class="footer-text">Desa Penebal - Bengkalis</div>
    </div>

</body>
</html>
