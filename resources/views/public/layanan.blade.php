@extends('layouts.public')

@section('title', 'Layanan Surat Online Mandiri - ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))

@section('content')
    <section class="section" style="padding-top: 48px;">
        <div class="container">
            <div class="section-header" style="margin-bottom: 40px; text-align: left; max-width: 100%;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Pelayanan Surat Online</h2>
                <p>Permohonan surat pengantar administrasi desa secara instan tanpa perlu mendaftar akun atau login.</p>
            </div>

            <!-- Alur Pengajuan (Infographic) -->
            <div class="card" style="margin-bottom: 48px;">
                <h3 style="font-size: 22px; margin-bottom: 32px; text-align: center; color: var(--primary-color); font-weight: 700;">Alur Pengajuan Surat Mandiri</h3>
                <div class="workflow-grid">
                    <div class="workflow-step">
                        <div class="workflow-circle">1</div>
                        <div class="workflow-text">
                            <h4 class="workflow-title">Pilih Surat</h4>
                            <p class="workflow-desc">Tentukan jenis surat pengantar</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="workflow-circle">2</div>
                        <div class="workflow-text">
                            <h4 class="workflow-title">Input NIK</h4>
                            <p class="workflow-desc">Validasi data & auto-fill otomatis</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="workflow-circle">3</div>
                        <div class="workflow-text">
                            <h4 class="workflow-title">Isi Form & File</h4>
                            <p class="workflow-desc">Lengkapi isian & upload KTP/KK</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="workflow-circle">4</div>
                        <div class="workflow-text">
                            <h4 class="workflow-title">Simpan Kode</h4>
                            <p class="workflow-desc">Salin kode pelacakan pengajuan</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="workflow-circle">5</div>
                        <div class="workflow-text">
                            <h4 class="workflow-title">Unduh PDF</h4>
                            <p class="workflow-desc">Selesai ditandatangani basah/stempel</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List Layanan Surat -->
            <div class="features-grid">
                <!-- 1. Domisili -->
                <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    <div>
                        <h3 style="margin-bottom: 12px; margin-top: 8px;">Surat Keterangan Domisili</h3>
                        <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                            Surat keterangan resmi yang menyatakan domisili/tempat tinggal seseorang di wilayah hukum {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }} untuk berbagai keperluan administratif (misal: buka rekening bank, melamar kerja).
                        </p>
                    </div>
                    <a href="{{ route('layanan.form', 'domisili') }}" class="btn btn-primary" style="width: 100%;">Pilih & Ajukan</a>
                </div>

                <!-- 2. SKTM -->
                <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    <div>
                        <h3 style="margin-bottom: 12px; margin-top: 8px;">Surat Keterangan Tidak Mampu (SKTM)</h3>
                        <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                            Surat keterangan yang diajukan oleh keluarga pra-sejahtera untuk mendapatkan keringanan biaya pendidikan, rujukan rumah sakit gratis, atau persyaratan penerima bantuan sosial pemerintah.
                        </p>
                    </div>
                    <a href="{{ route('layanan.form', 'sktm') }}" class="btn btn-primary" style="width: 100%;">Pilih & Ajukan</a>
                </div>

                <!-- 3. Pindah -->
                <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    <div>
                        <h3 style="margin-bottom: 12px; margin-top: 8px;">Surat Pindah Penduduk</h3>
                        <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                            Surat keterangan resmi perpindahan tempat tinggal seorang penduduk dari {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }} menuju wilayah administratif baru di luar desa, luar kecamatan, atau luar provinsi.
                        </p>
                    </div>
                    <a href="{{ route('layanan.form', 'pindah') }}" class="btn btn-primary" style="width: 100%;">Pilih & Ajukan</a>
                </div>
            </div>
            
            <!-- Quick Link to Tracking -->
            <div class="card" style="margin-top: 48px; display: flex; justify-content: space-between; align-items: center; background-color: var(--primary-light); border-color: rgba(37,99,235,0.15); flex-wrap: wrap; gap: 16px;">
                <div>
                    <h3 style="font-size: 18px; margin-bottom: 4px; color: var(--primary-color);">Sudah Pernah Mengajukan Surat?</h3>
                    <p style="font-size: 14px; color: var(--text-secondary);">Pantau perkembangan verifikasi berkas dan unduh PDF surat Anda langsung menggunakan kode pelacakan unik.</p>
                </div>
                <a href="{{ route('layanan.lacak') }}" class="btn btn-secondary" style="border-color: rgba(37,99,235,0.3); color: var(--primary-color);">Lacak Pengajuan ➔</a>
            </div>
        </div>
    </section>
@endsection
