@extends('layouts.admin')

@section('title', 'Kelola Template Surat DOCX')

@section('content')
    <div class="page-header" style="margin-bottom: 32px;">
        <div class="page-title">
            <h1>Kelola Template Surat</h1>
            <p>Unggah template dokumen Microsoft Word (.docx) untuk mengotomatisasi pembuatan surat pelayanan warga.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px; padding: 12px 16px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgb(16, 185, 129); color: rgb(6, 95, 70); border-radius: 6px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px; padding: 12px 16px; background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68); color: rgb(153, 27, 27); border-radius: 6px;">
            <ul style="margin: 0; padding-left: 16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Panduan Placeholders -->
    <div class="form-card" style="margin-bottom: 32px; background: var(--bg-card); border-left: 4px solid var(--primary-color);">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: var(--primary-color);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.083.984l-.04.02-1.084-.984zm0 0l-.041.02a.75.75 0 11-1.083-.984l.04-.02 1.084.984zm0 0V15m-3 4.5h10.5A2.25 2.25 0 0021 17.25V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5z" />
            </svg>
            Panduan Variabel Template Surat (.docx)
        </h3>
        <p style="font-size: 14px; line-height: 1.6; color: var(--text-muted); margin-bottom: 16px;">
            Anda dapat menggunakan variabel placeholder di dalam file Word (.docx) Anda dengan format <code>${nama_variabel}</code>. Sistem akan otomatis mendeteksi dan menggantinya dengan data pengajuan surat dan profil desa.
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            <div>
                <strong style="display: block; font-size: 13px; text-transform: uppercase; color: var(--text-dark); margin-bottom: 8px;">Variabel Global</strong>
                <ul style="font-size: 13px; padding-left: 20px; color: var(--text-muted); line-height: 1.8;">
                    <li><code>${nomor_surat}</code> - Nomor surat tergenerate otomatis</li>
                    <li><code>${nama}</code> - Nama lengkap pemohon</li>
                    <li><code>${nik}</code> - NIK pemohon</li>
                    <li><code>${tempat_lahir}</code> - Tempat lahir pemohon</li>
                    <li><code>${tanggal_lahir}</code> - Tanggal lahir pemohon</li>
                    <li><code>${jenis_kelamin}</code> - Laki-Laki / Perempuan</li>
                    <li><code>${agama}</code>, <code>${pekerjaan}</code> - Agama & Pekerjaan</li>
                    <li><code>${alamat}</code> - Alamat asal pemohon</li>
                </ul>
            </div>
            <div>
                <strong style="display: block; font-size: 13px; text-transform: uppercase; color: var(--text-dark); margin-bottom: 8px;">Profil Pemerintahan Desa</strong>
                <ul style="font-size: 13px; padding-left: 20px; color: var(--text-muted); line-height: 1.8;">
                    <li><code>${nama_desa}</code> - Nama desa aktif (cth: Desa Penebal)</li>
                    <li><code>${kecamatan}</code> - Kecamatan aktif</li>
                    <li><code>${kabupaten}</code> - Kabupaten aktif</li>
                    <li><code>${nama_kepala}</code> - Nama Kepala Desa</li>
                    <li><code>${tanggal_surat}</code> - Tanggal persetujuan surat</li>
                </ul>
            </div>
            <div>
                <strong style="display: block; font-size: 13px; text-transform: uppercase; color: var(--text-dark); margin-bottom: 8px;">Variabel Khusus Layanan</strong>
                <ul style="font-size: 13px; padding-left: 20px; color: var(--text-muted); line-height: 1.8;">
                    <li><strong>Domisili:</strong> <code>${alamat_domisili}</code>, <code>${keperluan}</code></li>
                    <li><strong>SKTM:</strong> <code>${keperluan}</code>, <code>${nama_sekolah_rs}</code></li>
                    <li><strong>Pindah:</strong> <code>${alamat_tujuan}</code>, <code>${rt_tujuan}</code>, <code>${rw_tujuan}</code>, <code>${dusun_tujuan}</code>, <code>${desa_tujuan}</code>, <code>${kecamatan_tujuan}</code>, <code>${kabupaten_tujuan}</code>, <code>${provinsi_tujuan}</code>, <code>${alasan_pindah}</code></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h3 style="font-size: 16px; font-weight: 700;">Daftar Template Surat Aktif</h3>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Nama Jenis Surat</th>
                        <th style="width: 15%;">Kode Tipe</th>
                        <th style="width: 25%;">Terakhir Diperbarui</th>
                        <th style="width: 30%; text-align: right;">Aksi & Unggah Ulang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                        <tr>
                            <td>
                                <div>
                                    <strong style="font-size: 15px; display: block; color: var(--text-dark);">{{ $template->nama_surat }}</strong>
                                    <span style="font-size: 12px; color: var(--text-muted);">{{ basename($template->file_path) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary" style="font-family: monospace; text-transform: uppercase;">{{ $template->jenis_surat }}</span>
                            </td>
                            <td>
                                <span style="font-size: 14px; color: var(--text-dark);">
                                    {{ $template->updated_at ? $template->updated_at->translatedFormat('d M Y, H:i') : 'Default Seeder' }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                                    <div style="display: flex; gap: 8px;">
                                        <!-- Preview Button -->
                                        <a href="{{ route('admin.surat.templates.preview', $template->jenis_surat) }}" target="_blank" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 6px; font-weight: 500;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Lihat
                                        </a>

                                        <!-- Download Button -->
                                        <a href="{{ route('admin.surat.templates.download', $template->jenis_surat) }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 6px; font-weight: 500;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Unduh .docx
                                        </a>

                                        <!-- Trigger Form File Upload -->
                                        <button onclick="document.getElementById('upload-form-{{ $template->jenis_surat }}').style.display = 'block'; this.style.display = 'none';" class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 6px; font-weight: 500;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                            Ganti Template
                                        </button>
                                    </div>

                                    <!-- Upload Form Block (Hidden Initially) -->
                                    <div id="upload-form-{{ $template->jenis_surat }}" style="display: none; width: 100%; margin-top: 8px; max-width: 320px; padding: 12px; background: var(--bg-page); border: 1px solid var(--border-color); border-radius: 8px;">
                                        <form action="{{ route('admin.surat.templates.store', $template->jenis_surat) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-dark);">Pilih File Baru (.docx)</label>
                                            <input type="file" name="template_file" accept=".docx" required style="font-size: 12px; display: block; width: 100%; margin-bottom: 8px;">
                                            <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                                <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display = 'none'; this.parentElement.parentElement.parentElement.previousElementSibling.firstElementChild.nextElementSibling.style.display = 'flex';" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 4px 8px;">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px; padding: 4px 8px; background-color: var(--primary-color);">
                                                    Unggah
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-light); padding: 40px;">
                                Belum ada data template terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
