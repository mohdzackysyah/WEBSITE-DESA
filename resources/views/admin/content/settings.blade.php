@extends('layouts.admin')

@section('title', 'Profil & Informasi Desa')

@section('content')
    <div class="page-header" style="margin-bottom: 32px;">
        <div class="page-title">
            <h1>Profil & Pengaturan Desa</h1>
            <p>Perbarui informasi sejarah, visi misi, kontak layanan, dan sambutan Kepala Desa yang ditayangkan pada portal publik.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="padding-left: 16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.content.settings.update') }}" method="POST">
            @csrf

            <!-- 1. IDENTITAS KEPALA DESA -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px;">Identitas & Sambutan Kepala Desa</h3>
            
            <div class="form-group">
                <label for="nama_kepala">Nama Lengkap Kepala Desa</label>
                <input 
                    type="text" 
                    name="nama_kepala" 
                    id="nama_kepala" 
                    value="{{ old('nama_kepala', $settings['nama_kepala']) }}" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="sambutan">Kata Sambutan Kepala Desa</label>
                <textarea 
                    name="sambutan" 
                    id="sambutan" 
                    class="form-control" 
                    style="min-height: 120px;" 
                    required
                >{{ old('sambutan', $settings['sambutan']) }}</textarea>
            </div>

            <!-- 2. SEJARAH & VISI MISI -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px; margin-top: 40px;">Sejarah & Visi Misi Desa</h3>
            
            <div class="form-group">
                <label for="sejarah">Sejarah Singkat Desa</label>
                <textarea 
                    name="sejarah" 
                    id="sejarah" 
                    class="form-control" 
                    style="min-height: 160px;" 
                    required
                >{{ old('sejarah', $settings['sejarah']) }}</textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="visi">Visi Desa</label>
                    <textarea 
                        name="visi" 
                        id="visi" 
                        class="form-control" 
                        style="min-height: 120px;" 
                        required
                    >{{ old('visi', $settings['visi']) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="misi">Misi Desa (Pisahkan dengan baris baru)</label>
                    <textarea 
                        name="misi" 
                        id="misi" 
                        class="form-control" 
                        style="min-height: 120px;" 
                        required
                    >{{ old('misi', $settings['misi']) }}</textarea>
                </div>
            </div>

            <!-- 3. ALAMAT & KONTAK -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px; margin-top: 40px;">Informasi Alamat, Kontak, & Operasional</h3>
            
            <div class="form-group">
                <label for="alamat">Alamat Lengkap Kantor Desa</label>
                <input 
                    type="text" 
                    name="alamat" 
                    id="alamat" 
                    value="{{ old('alamat', $settings['alamat']) }}" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="kontak_telepon">Nomor Telepon Kantor</label>
                    <input 
                        type="text" 
                        name="kontak_telepon" 
                        id="kontak_telepon" 
                        value="{{ old('kontak_telepon', $settings['kontak_telepon']) }}" 
                        class="form-control" 
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="kontak_email">Alamat Email Resmi</label>
                    <input 
                        type="email" 
                        name="kontak_email" 
                        id="kontak_email" 
                        value="{{ old('kontak_email', $settings['kontak_email']) }}" 
                        class="form-control" 
                        required
                    >
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="jam_pelayanan">Jam Pelayanan Kantor (Preserve baris baru)</label>
                    <textarea 
                        name="jam_pelayanan" 
                        id="jam_pelayanan" 
                        class="form-control" 
                        style="min-height: 100px;" 
                        required
                    >{{ old('jam_pelayanan', $settings['jam_pelayanan']) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="peta_lokasi">Link Iframe Google Maps (Peta Lokasi)</label>
                    <textarea 
                        name="peta_lokasi" 
                        id="peta_lokasi" 
                        class="form-control" 
                        style="min-height: 100px;" 
                        placeholder="Contoh: https://www.google.com/maps/embed?..." 
                        required
                    >{{ old('peta_lokasi', $settings['peta_lokasi']) }}</textarea>
                    <span style="font-size: 11px; color: var(--text-light); margin-top: 4px; display: block;">Masukkan URL `src` saja dari kode embed HTML yang diberikan Google Maps.</span>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; display: inline-flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    Simpan Semua Perubahan Pengaturan
                </button>
            </div>

        </form>
    </div>
@endsection
