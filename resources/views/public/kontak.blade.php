@extends('layouts.public')

@section('title', 'Hubungi Kami - Kantor ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))

@section('styles')
<style>
    .contact-section {
        padding: 64px 0;
        background-color: var(--bg-main);
    }
    .contact-grid {
        display: grid;
        grid-template-columns: 1.2fr 1.8fr;
        gap: 40px;
        margin-top: 32px;
    }
    @media (max-width: 992px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }
    .contact-info-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        padding: 32px;
        height: fit-content;
    }
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }
    .contact-item:last-child {
        margin-bottom: 0;
    }
    .contact-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: var(--primary-light);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        border: 1px solid rgba(11, 102, 62, 0.1);
    }
    .contact-details h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 4px;
    }
    .contact-details p {
        font-size: 13.5px;
        color: var(--text-secondary);
        line-height: 1.6;
        white-space: pre-line;
    }
    .contact-form-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        padding: 32px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        font-family: inherit;
        font-size: 14px;
        color: var(--text-primary);
        transition: var(--transition-fast);
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(11, 102, 62, 0.1);
    }
    textarea.form-control {
        resize: vertical;
        min-height: 140px;
    }
    .btn-submit {
        background-color: var(--primary-color);
        color: var(--text-white);
        border: none;
        padding: 12px 28px;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition-fast);
        box-shadow: 0 4px 10px rgba(11, 102, 62, 0.2);
    }
    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(11, 102, 62, 0.3);
    }
    .map-container {
        border-radius: var(--radius-md);
        overflow: hidden;
        height: 350px;
        border: 1px solid var(--border-color);
        margin-top: 40px;
        box-shadow: var(--shadow-md);
    }
</style>
@endsection

@section('content')
    <section class="contact-section">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Hubungi Kantor Desa</h2>
                <p style="max-width: 600px; margin: 10px auto 0;">Punya pertanyaan, keluhan, saran pembangunan, atau butuh bantuan layanan warga? Hubungi kami secara online melalui formulir di bawah ini.</p>
            </div>

            <!-- Display Session Alerts -->
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 30px; text-align: center;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 30px;">
                    <ul style="padding-left: 20px; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="contact-grid">
                
                <!-- 1. INFO KONTAK KANTOR DESA -->
                <div class="contact-info-card">
                    <h3 style="font-size: 18px; margin-bottom: 24px; color: var(--secondary-color); border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">Informasi Kontak</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div class="contact-details">
                            <h4>Alamat Kantor</h4>
                            <p>{{ $alamat }}</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div class="contact-details">
                            <h4>Telepon Pelayanan</h4>
                            <p>{{ $kontak_telepon }}</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div class="contact-details">
                            <h4>Email Resmi</h4>
                            <p>{{ $kontak_email }}</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="contact-details">
                            <h4>Jam Kerja Pelayanan</h4>
                            <p>{{ $jam_pelayanan }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. FORMULIR HUBUNGI KAMI -->
                <div class="contact-form-card">
                    <h3 style="font-size: 18px; margin-bottom: 24px; color: var(--secondary-color); border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">Kirim Pesan</h3>
                    
                    <form action="{{ route('kontak.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span style="color: red;">*</span></label>
                            <input 
                                type="text" 
                                name="nama" 
                                id="nama" 
                                value="{{ old('nama') }}"
                                class="form-control" 
                                placeholder="Nama Lengkap Anda" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK (16 Digit Angka - Opsional)</label>
                            <input 
                                type="text" 
                                name="nik" 
                                id="nik" 
                                value="{{ old('nik') }}"
                                class="form-control" 
                                placeholder="Nomor Induk Kependudukan Anda" 
                                maxlength="16"
                                pattern="\d{16}"
                                title="NIK harus berupa 16 digit angka"
                            >
                        </div>

                        <div class="form-group">
                            <label for="subjek">Subjek Pesan <span style="color: red;">*</span></label>
                            <input 
                                type="text" 
                                name="subjek" 
                                id="subjek" 
                                value="{{ old('subjek') }}"
                                class="form-control" 
                                placeholder="Subjek pengaduan/pertanyaan" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="pesan">Isi Pesan <span style="color: red;">*</span></label>
                            <textarea 
                                name="pesan" 
                                id="pesan" 
                                class="form-control" 
                                placeholder="Tuliskan pesan atau pertanyaan Anda di sini secara detail..." 
                                required
                            >{{ old('pesan') }}</textarea>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" class="btn-submit">Kirim Pesan ➔</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MAP SECTION -->
            <div class="map-container">
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
    </section>
@endsection
