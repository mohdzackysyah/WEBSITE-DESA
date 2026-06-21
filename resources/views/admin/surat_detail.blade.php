@extends('layouts.admin')

@section('title', 'Detail Pengajuan - ' . $surat->nomor_pengajuan)

@section('content')
    <!-- Back Button & Title -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.dashboard') }}" style="color: var(--text-light); display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke Daftar Pengajuan
        </a>
    </div>

    <div class="page-header" style="margin-bottom: 32px;">
        <div class="page-title">
            <h1>Pengajuan {{ $surat->nomor_pengajuan }}</h1>
            <p>Tinjau detail data kependudukan dan ubah status pengajuan surat warga.</p>
        </div>
        <div>
            <span class="badge badge-{{ $surat->status }}" style="font-size: 14px; padding: 6px 12px;">
                {{ $surat->status_label }}
            </span>
        </div>
    </div>

    <div class="grid-2" style="grid-template-columns: 1.5fr 1fr; align-items: start;">
        <!-- LEFT COLUMN: DETAIL DATA -->
        <div>
            <!-- Detail Pemohon -->
            <div class="detail-card">
                <div class="detail-card-header">Data Pemohon (Sesuai Formulir)</div>
                <div class="detail-card-body">
                    <div class="info-list-row">
                        <div class="info-list-label">Nama Lengkap</div>
                        <div class="info-list-value" style="font-weight: 600;">{{ $surat->nama_lengkap }}</div>
                    </div>
                    <div class="info-list-row">
                        <div class="info-list-label">NIK</div>
                        <div class="info-list-value" style="font-family: monospace;">{{ $surat->nik }}</div>
                    </div>
                    <div class="info-list-row">
                        <div class="info-list-label">Jenis Surat</div>
                        <div class="info-list-value" style="font-weight: 600;">
                            {{ match($surat->jenis_surat) { 'domisili' => 'Surat Keterangan Domisili', 'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)', 'pindah' => 'Surat Pindah Penduduk', default => $surat->jenis_surat } }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Spesifik Form -->
            <div class="detail-card">
                <div class="detail-card-header">Data Isian Surat</div>
                <div class="detail-card-body">
                    @if($surat->jenis_surat === 'domisili')
                        <div class="info-list-row">
                            <div class="info-list-label">Alamat Domisili</div>
                            <div class="info-list-value">{{ $surat->form_data['alamat_domisili'] ?? '-' }}</div>
                        </div>
                        <div class="info-list-row">
                            <div class="info-list-label">Keperluan / Tujuan</div>
                            <div class="info-list-value">{{ $surat->form_data['keperluan'] ?? '-' }}</div>
                        </div>
                    @elseif($surat->jenis_surat === 'sktm')
                        <div class="info-list-row">
                            <div class="info-list-label">Keperluan / Tujuan</div>
                            <div class="info-list-value">{{ $surat->form_data['keperluan'] ?? '-' }}</div>
                        </div>
                        <div class="info-list-row">
                            <div class="info-list-label">Nama Sekolah / Rumah Sakit</div>
                            <div class="info-list-value">{{ $surat->form_data['nama_sekolah_rs'] ?? '-' }}</div>
                        </div>
                        <div class="info-list-row">
                            <div class="info-list-label">Penghasilan Orang Tua</div>
                            <div class="info-list-value">Rp {{ number_format($surat->form_data['penghasilan_orang_tua'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    @elseif($surat->jenis_surat === 'pindah')
                        <div class="info-list-row">
                            <div class="info-list-label">Alamat Tujuan Pindah</div>
                            <div class="info-list-value">{{ $surat->form_data['alamat_tujuan'] ?? '-' }}</div>
                        </div>
                        <div class="info-list-row">
                            <div class="info-list-label">RT/RW Tujuan</div>
                            <div class="info-list-value">RT {{ $surat->form_data['rt_tujuan'] ?? '-' }} RW {{ $surat->form_data['rw_tujuan'] ?? '-' }}</div>
                        </div>
                        <div class="info-list-row">
                            <div class="info-list-label">Wilayah Tujuan</div>
                            <div class="info-list-value">
                                Dusun {{ $surat->form_data['dusun_tujuan'] ?? '-' }}, 
                                Desa {{ $surat->form_data['desa_tujuan'] ?? '-' }}, 
                                Kec. {{ $surat->form_data['kecamatan_tujuan'] ?? '-' }}, 
                                Kab. {{ $surat->form_data['kabupaten_tujuan'] ?? '-' }}, 
                                Prov. {{ $surat->form_data['provinsi_tujuan'] ?? '-' }}
                            </div>
                        </div>
                        <div class="info-list-row">
                            <div class="info-list-label">Alasan Pindah</div>
                            <div class="info-list-value">{{ $surat->form_data['alasan_pindah'] ?? '-' }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Berkas Pendukung -->
            <div class="detail-card">
                <div class="detail-card-header">Berkas Lampiran Pendukung</div>
                <div class="detail-card-body" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>Dokumen KTP / Kartu Keluarga Scan</strong>
                        <p style="font-size: 12px; color: var(--text-light); margin-top: 4px;">Periksa berkas untuk memverifikasi keaslian pengajuan warga.</p>
                    </div>
                    <a href="{{ route('admin.surat.attachment', $surat->id) }}" target="_blank" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Tinjau Berkas
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ACTIONS -->
        <div>
            <!-- Tindakan Operator -->
            <div class="detail-card">
                <div class="detail-card-header">Tindakan Pelayanan</div>
                <div class="detail-card-body">
                    
                    @if($surat->status === 'menunggu_verifikasi')
                        <!-- OPSI MENUNGU VERIFIKASI -->
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <p style="font-size: 13px; color: var(--text-secondary);">
                                Berkas baru masuk. Silakan tinjau berkas lampiran pendukung terlebih dahulu. Jika data sesuai, mulai proses pembuatan draf surat.
                            </p>
                            
                            <form action="{{ route('admin.surat.status', $surat->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="btn btn-primary" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.297 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.214-1.28z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Mulai Proses Surat
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger" onclick="toggleRejectionForm(true)" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak Pengajuan
                            </button>
                        </div>

                    @elseif($surat->status === 'diproses')
                        <!-- OPSI DIPROSES -->
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <div style="background-color: var(--primary-light); padding: 12px; border-radius: var(--radius-sm); border: 1px solid rgba(37,99,235,0.15);">
                                <strong style="color: var(--primary-color); font-size: 13px;">Nomor Surat Resmi Dihasilkan:</strong>
                                <p style="font-family: monospace; font-size: 15px; font-weight: 700; margin-top: 4px;">{{ $surat->nomor_surat }}</p>
                            </div>

                            <a href="{{ route('admin.surat.preview-draft', $surat->id) }}" target="_blank" class="btn btn-secondary" style="width: 100%; justify-content: center; background-color: #f8fafc; cursor: pointer; border: 1px solid var(--border-color); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; line-height: 1.5;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.244 2.077H8.584A2.25 2.25 0 016.34 18m11.318-4.171c.277-.03.553-.063.828-.098m-12.146.098c-.277-.03-.553-.063-.828-.098m12.974-.014c.257-.03.513-.062.769-.096m-14.512.096c-.257-.03-.513-.062-.769-.096m15.281-.02a9 9 0 01-15.28 0m15.28 0a9 9 0 00-15.28 0M15 9.75H9m6 3H9m3-6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                                Cetak / Unduh Draf Surat PDF
                            </a>

                            <div style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 8px;">
                                <strong style="font-size: 14px; display: block; margin-bottom: 8px;">Unggah Scan Surat Final</strong>
                                <p style="font-size: 12px; color: var(--text-light); margin-bottom: 12px;">Cetak draf surat, mintakan tanda tangan Kepala Desa & stempel basah, scan lalu unggah versi finalnya di bawah ini untuk menyelesaikannya.</p>
                                
                                <form action="{{ route('admin.surat.status', $surat->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="status" value="selesai">
                                    
                                    <div class="form-group">
                                        <input type="file" name="dokumen_final" accept=".pdf" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="catatan_operator">Catatan Untuk Warga (Opsional)</label>
                                        <input type="text" name="catatan_operator" id="catatan_operator" class="form-control" placeholder="Contoh: Surat selesai ditandatangani.">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary" style="width: 100%; background-color: var(--accent-color); display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        Selesaikan & Publish Surat
                                    </button>
                                </form>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm" onclick="toggleRejectionForm(true)" style="width: 100%; margin-top: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak Pengajuan
                            </button>
                        </div>

                    @elseif($surat->status === 'selesai')
                        <!-- OPSI SELESAI -->
                        <div style="display: flex; flex-direction: column; gap: 16px; text-align: center; align-items: center;">
                            <div style="color: var(--accent-color); background-color: var(--accent-light); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 style="color: var(--accent-color);">Pengajuan Selesai Diproses</h4>
                            <p style="font-size: 13px; color: var(--text-secondary);">Nomor Surat: <strong>{{ $surat->nomor_surat }}</strong></p>
                            
                            @if($surat->dokumen_final)
                                <a href="{{ route('admin.surat.preview-final', $surat->id) }}" target="_blank" class="btn btn-primary" style="width: 100%; background-color: var(--accent-color); display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; border: none; line-height: 1.5;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Lihat & Cetak Surat Final
                                </a>
                            @endif
                        </div>

                    @elseif($surat->status === 'ditolak')
                        <!-- OPSI DITOLAK -->
                        <div style="display: flex; flex-direction: column; gap: 12px; background-color: #fef2f2; border: 1px solid #fee2e2; padding: 16px; border-radius: var(--radius-sm); color: #991b1b;">
                            <strong>Pengajuan Ditolak</strong>
                            <p style="font-size: 13px; margin-bottom: 0;">
                                <strong>Alasan Penolakan:</strong><br>
                                "{{ $surat->alasan_penolakan }}"
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Form Penolakan (Hidden by default) -->
            <div class="detail-card" id="rejection-card" style="display: none; border-color: #ef4444;">
                <div class="detail-card-header" style="background-color: #fef2f2; color: #b91c1c;">Form Penolakan Pengajuan</div>
                <div class="detail-card-body">
                    <form action="{{ route('admin.surat.status', $surat->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="ditolak">
                        
                        <div class="form-group">
                            <label for="alasan_penolakan" style="color: #b91c1c;">Alasan Penolakan (Wajib Diisi)</label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" placeholder="Contoh: Berkas KK kurang jelas / NIK tidak sesuai nama pemohon." required></textarea>
                        </div>
                        
                        <div style="display: flex; gap: 8px; margin-top: 16px;">
                            <button type="button" class="btn btn-secondary" onclick="toggleRejectionForm(false)" style="flex-grow: 1;">Batal</button>
                            <button type="submit" class="btn btn-danger" style="flex-grow: 1;">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function toggleRejectionForm(show) {
        const card = document.getElementById('rejection-card');
        card.style.display = show ? 'block' : 'none';
        if (show) {
            window.scrollTo(0, document.body.scrollHeight);
        }
    }


</script>
@endsection
