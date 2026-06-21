<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <!-- Outfit & Plus Jakarta Sans fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <style>
        :root {
            --primary-color: #0b663e;
            --primary-hover: #074e2f;
            --primary-light: #f0fdf4;
            --secondary-color: #0b2216;
            --accent-color: #10b981;
            
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
            background: radial-gradient(circle at 10% 20%, rgba(240, 253, 244, 0.5) 0%, rgba(248, 250, 252, 1) 90%);
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
            background-color: rgba(16, 185, 129, 0.2);
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
        .search-lens {
            animation: searchOrbit 5s ease-in-out infinite;
            transform-origin: 110px 110px;
        }

        @keyframes searchOrbit {
            0%, 100% { transform: rotate(0deg) translate(0, 0); }
            33% { transform: rotate(15deg) translate(8px, -5px); }
            66% { transform: rotate(-15deg) translate(-8px, 5px); }
        }

        .floating-element {
            animation: elementFloat 4s ease-in-out infinite alternate;
        }

        .floating-element-delay {
            animation: elementFloat 4s ease-in-out infinite alternate-reverse;
        }

        @keyframes elementFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-8px); }
        }

        .error-code {
            font-family: 'Outfit', sans-serif;
            font-size: 80px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
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
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: var(--primary-light);
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
                <!-- Base Desk/Shadow -->
                <ellipse cx="110" cy="180" rx="70" ry="12" fill="#E2E8F0" />
                
                <!-- Map / Document Folder -->
                <rect class="floating-element" x="50" y="60" width="120" height="90" rx="10" fill="#0b663e" fill-opacity="0.1" stroke="#0b663e" stroke-width="3" />
                <path class="floating-element" d="M50 85H170" stroke="#0b663e" stroke-width="2" stroke-dasharray="4 4" />
                
                <!-- Map Contours -->
                <path class="floating-element" d="M70 120C85 110 100 135 115 125C130 115 145 130 155 115" stroke="#10b981" stroke-width="3" stroke-linecap="round" />
                <circle class="floating-element" cx="70" cy="120" r="4" fill="#0b663e" />
                <circle class="floating-element" cx="155" cy="115" r="4" fill="#10b981" />
                
                <!-- Small Floating Gear/Stars -->
                <g class="floating-element-delay">
                    <circle cx="40" cy="50" r="3" fill="#10b981" />
                    <circle cx="185" cy="80" r="4" fill="#0b663e" />
                    <circle cx="170" cy="155" r="2.5" fill="#94A3B8" />
                </g>

                <!-- Magnifying Glass (Search Lens) -->
                <g class="search-lens">
                    <!-- Handle -->
                    <path d="M128 128L158 158" stroke="#1E293B" stroke-width="8" stroke-linecap="round" />
                    <!-- Outer Ring -->
                    <circle cx="110" cy="110" r="25" fill="#F8FAFC" stroke="#1E293B" stroke-width="6" />
                    <!-- Glass Reflection -->
                    <path d="M96 100C100 96 108 94 112 98" stroke="#94A3B8" stroke-width="2.5" stroke-linecap="round" />
                </g>
            </svg>
        </div>

        <div class="error-code">404</div>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>Maaf, halaman yang Anda tuju tidak ditemukan. Pastikan alamat URL sudah benar atau halaman telah dipindahkan.</p>

        <div class="button-group">
            <a href="/" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                Kembali ke Beranda
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
