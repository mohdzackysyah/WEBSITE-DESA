@extends('layouts.public')

@section('title', 'Galeri Foto Kegiatan ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal') . ' - Portal Resmi')

@section('styles')
<style>
    /* Premium Responsive Auto-fit Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important;
        gap: 24px !important;
    }

    /* Filter Buttons wrapping on smaller screens */
    @media (max-width: 768px) {
        .gallery-filter {
            flex-wrap: wrap;
            gap: 8px !important;
            margin-bottom: 24px !important;
            padding: 0 8px;
        }
        .filter-btn {
            padding: 8px 16px !important;
            font-size: 13px !important;
            flex-grow: 1;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
        }
        .gallery-image {
            height: 200px !important; /* Shorter card heights on narrow phones */
        }
        .gallery-overlay {
            padding: 16px !important;
        }
        .gallery-overlay h4 {
            font-size: 14px !important;
        }
    }
</style>
@endsection

@section('content')
    <section class="section" style="padding-top: 48px; min-height: 70vh;">
        <div class="container">
            <div class="section-header" style="margin-bottom: 40px; text-align: left; max-width: 100%;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Galeri Foto Desa</h2>
                <p>Dokumentasi visual kegiatan masyarakat, pembangunan fasilitas, dan momentum kebersamaan di {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }}.</p>
            </div>

            <!-- Client-side Category Filter Buttons -->
            <div class="gallery-filter">
                <button class="filter-btn active" onclick="filterGallery('all', this)">Semua</button>
                <button class="filter-btn" onclick="filterGallery('kegiatan', this)">Kegiatan</button>
                <button class="filter-btn" onclick="filterGallery('pembangunan', this)">Pembangunan</button>
                <button class="filter-btn" onclick="filterGallery('fasilitas', this)">Fasilitas</button>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid" id="gallery-container">
                @forelse($galleries as $g)
                    <div class="gallery-card" data-category="{{ $g->category }}">
                        <img src="{{ $g->image }}" alt="{{ $g->title }}" class="gallery-image" loading="lazy">
                        <div class="gallery-overlay">
                            <h4>{{ $g->title }}</h4>
                            <span>{{ $g->category }}</span>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 3; text-align: center; color: var(--text-light); padding: 80px;">
                        Belum ada foto dokumentasi yang diunggah.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    function filterGallery(category, button) {
        // Toggle active button class
        const buttons = document.querySelectorAll('.filter-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Filter cards
        const cards = document.querySelectorAll('.gallery-card');
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (category === 'all' || cardCat === category) {
                card.style.display = 'block';
                // Trigger transition
                setTimeout(() => card.style.opacity = '1', 50);
            } else {
                card.style.opacity = '0';
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection
