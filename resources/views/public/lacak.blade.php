@extends('layouts.public')

@section('title', 'Lacak Status Pengajuan Surat - ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))

@section('content')
    <section class="section" style="padding-top: 48px; min-height: 70vh;">
        <div class="container" style="max-width: 800px;">
            <div class="section-header" style="margin-bottom: 40px; text-align: center;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Lacak Status Pengajuan</h2>
                <p>Masukkan kode pelacakan unik dan NIK Anda untuk memantau perkembangan permohonan surat secara real-time.</p>
            </div>

            <!-- Form Pencarian Lacak -->
            <div class="card" style="margin-bottom: 32px; padding: 24px;">
                <form action="{{ route('layanan.lacak') }}" method="GET" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="kode_pelacakan">Kode Pelacakan</label>
                        <input 
                            type="text" 
                            name="kode_pelacakan" 
                            id="kode_pelacakan" 
                            value="{{ request('kode_pelacakan', session('success_code')) }}" 
                            placeholder="Contoh: DSA-2026-AB12CD" 
                            class="form-control"
                            required
                        >
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="nik">NIK Pemohon</label>
                        <input 
                            type="text" 
                            name="nik" 
                            id="nik" 
                            value="{{ request('nik', session('search_nik')) }}" 
                            placeholder="16 Digit NIK Anda" 
                            class="form-control"
                            required
                        >
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 46px; padding: 0 24px;">Cari Status</button>
                </form>
            </div>

            <!-- Hasil Pencarian -->
            @if($searched)
                @if($surat)
                    <div class="tracking-result">
                        <!-- Stepper Visual -->
                        <div class="stepper">
                            <!-- Step 1: Menunggu Verifikasi -->
                            <div class="step-item completed">
                                <div class="step-circle">1</div>
                                <div class="step-label">Pengajuan Masuk</div>
                            </div>

                            <!-- Step 2: Diproses -->
                            @php
                                $isDiproses = in_array($surat->status, ['diproses', 'selesai']);
                                $isSelesai = $surat->status === 'selesai';
                                $isDitolak = $surat->status === 'ditolak';
                            @endphp
                            <div class="step-item {{ $isDiproses ? 'completed' : ($isDitolak ? '' : 'active') }}">
                                <div class="step-circle">2</div>
                                <div class="step-label">Diproses Operator</div>
                            </div>

                            <!-- Step 3: Selesai / Ditolak -->
                            @if($isDitolak)
                                <div class="step-item rejected">
                                    <div class="step-circle">✘</div>
                                    <div class="step-label" style="color: #ef4444;">Pengajuan Ditolak</div>
                                </div>
                            @else
                                <div class="step-item {{ $isSelesai ? 'completed' : '' }}">
                                    <div class="step-circle">3</div>
                                    <div class="step-label">Surat Selesai</div>
                                </div>
                            @endif
                        </div>

                        <!-- Info Detail Pengajuan -->
                        <div style="border-top: 1px solid var(--border-color); padding-top: 24px; margin-top: 32px;">
                            <h3 style="font-size: 18px; margin-bottom: 20px; color: var(--secondary-color);">Rincian Status Surat</h3>
                            
                            <div class="detail-list">
                                <div class="detail-row">
                                    <div class="detail-label">Nomor Pengajuan</div>
                                    <div class="detail-value" style="font-family: monospace; font-weight: 700; color: var(--primary-color);">{{ $surat->nomor_pengajuan }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Jenis Surat</div>
                                    <div class="detail-value" style="font-weight: 600;">
                                        {{ match($surat->jenis_surat) { 'domisili' => 'Surat Keterangan Domisili', 'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)', 'pindah' => 'Surat Pindah Penduduk', default => $surat->jenis_surat } }}
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Nomor Surat Resmi</div>
                                    <div class="detail-value">{{ $surat->nomor_surat ?? 'Belum diterbitkan' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Nama Pemohon</div>
                                    <div class="detail-value">{{ $surat->nama_lengkap }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">NIK Pemohon</div>
                                    <div class="detail-value">{{ $surat->nik }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Tanggal Pengajuan</div>
                                    <div class="detail-value">{{ $surat->created_at->translatedFormat('d F Y H:i') }} WIB</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Status Terkini</div>
                                    <div class="detail-value">
                                        <span class="badge badge-{{ $surat->status }}" style="display: inline-block; padding: 4px 10px; border-radius: var(--radius-sm); font-size: 13px; font-weight: bold;
                                            @if($surat->status === 'menunggu_verifikasi') background-color: #fef3c7; color: #d97706;
                                            @elseif($surat->status === 'diproses') background-color: #e0f2fe; color: #0284c7;
                                            @elseif($surat->status === 'selesai') background-color: #d1fae5; color: #059669;
                                            @elseif($surat->status === 'ditolak') background-color: #fee2e2; color: #dc2626;
                                            @endif
                                        ">
                                            {{ $surat->status_label }}
                                        </span>
                                    </div>
                                </div>

                                @if($surat->catatan_operator)
                                    <div class="detail-row">
                                        <div class="detail-label">Catatan Operator</div>
                                        <div class="detail-value" style="font-style: italic; color: var(--text-secondary);">"{{ $surat->catatan_operator }}"</div>
                                    </div>
                                @endif

                                @if($isDitolak && $surat->alasan_penolakan)
                                    <div class="detail-row" style="background-color: #fef2f2; padding: 12px; border-radius: var(--radius-sm);">
                                        <div class="detail-label" style="color: #b91c1c;">Alasan Penolakan</div>
                                        <div class="detail-value" style="color: #b91c1c; font-weight: 600;">{{ $surat->alasan_penolakan }}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Area Lihat PDF -->
                            @if($isSelesai && $surat->dokumen_final)
                                <div style="margin-top: 32px; text-align: center; background-color: #ecfdf5; border: 1px solid #d1fae5; padding: 24px; border-radius: var(--radius-md);">
                                    <h4 style="color: #065f46; margin-bottom: 8px;">Surat Anda Telah Selesai Dibuat!</h4>
                                    <p style="font-size: 13px; color: #047857; margin-bottom: 20px;">Silakan lihat dokumen PDF resmi di bawah ini. Anda dapat langsung mencetak atau mengunduh surat tersebut.</p>
                                    <a href="{{ route('layanan.preview', $surat->id) }}" target="_blank" class="btn btn-primary" style="background-color: #059669; box-shadow: 0 4px 12px rgba(5,150,105,0.2); display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: none; line-height: 1.5;">
                                        👁️ Lihat & Cetak Surat PDF Resmi
                                    </a>
                                </div>
                            @elseif($isDiproses)
                                <div style="margin-top: 32px; text-align: center; background-color: #f0f9ff; border: 1px solid #e0f2fe; padding: 24px; border-radius: var(--radius-md); color: #0369a1;">
                                    <h4 style="margin-bottom: 8px;">Surat Sedang Diproses Operator</h4>
                                    <p style="font-size: 13px; margin-bottom: 0;">Dokumen draf surat sedang disiapkan, dicetak, dan diajukan tanda tangan pejabat desa. Halaman ini akan ter-update otomatis jika selesai.</p>
                                </div>
                            @elseif($surat->status === 'menunggu_verifikasi')
                                <div style="margin-top: 32px; text-align: center; background-color: #fffbeb; border: 1px solid #fef3c7; padding: 24px; border-radius: var(--radius-md); color: #b45309;">
                                    <h4 style="margin-bottom: 8px;">Menunggu Verifikasi Berkas</h4>
                                    <p style="font-size: 13px; margin-bottom: 0;">Permohonan berkas Anda sudah masuk ke sistem desa. Operator pelayanan akan memeriksa kesesuaian lampiran KTP/KK Anda segera.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger" style="text-align: center; padding: 32px;">
                        <span style="font-size: 32px; display: block; margin-bottom: 12px;">🔍</span>
                        <strong>Pengajuan Tidak Ditemukan!</strong><br>
                        Tidak ada berkas dengan Kode Pelacakan <strong>{{ request('kode_pelacakan') }}</strong> dan NIK <strong>{{ request('nik') }}</strong> yang cocok di database kami. Pastikan Anda menyalin kode pelacakan dengan benar.
                    </div>
                @endif
            @else
                <!-- Info Banner Awal -->
                @if(session('success_code'))
                    <div class="alert alert-success" style="padding: 24px; border-radius: var(--radius-md); margin-bottom: 24px;">
                        <h4 style="font-size: 18px; margin-bottom: 8px;">🎉 Pengajuan Surat Berhasil Dikirim!</h4>
                        <p style="font-size: 14px; margin-bottom: 16px;">
                            Permohonan Anda berhasil terdaftar. Catat kode pelacakan unik di bawah ini untuk memeriksa status surat di kemudian hari secara mandiri.
                        </p>
                        <div style="background-color: white; border: 2px dashed var(--primary-color); padding: 12px; font-family: monospace; font-size: 20px; font-weight: 700; text-align: center; color: var(--primary-color); border-radius: var(--radius-sm); letter-spacing: 2px; margin-bottom: 20px;">
                            {{ session('success_code') }}
                        </div>
                        <p style="font-size: 12px; color: var(--text-secondary); margin-bottom: 0;">
                            Gunakan form di atas dengan menekan tombol <strong>"Cari Status"</strong> untuk memantau status secara langsung sekarang.
                        </p>
                    </div>
                @else
                    <div class="alert alert-success" style="background-color: var(--primary-light); color: var(--primary-color); border-color: rgba(37,99,235,0.1); padding: 24px; border-radius: var(--radius-md);">
                        <h4>💡 Cara Melacak Surat Anda:</h4>
                        <ol style="margin-top: 12px; padding-left: 20px; font-size: 14px; line-height: 1.8;">
                            <li>Masukkan <strong>Kode Pelacakan Unik</strong> (misal: <em>DSA-2026-AB12CD</em>) yang Anda dapatkan setelah menekan tombol submit form pengajuan.</li>
                            <li>Masukkan 16 digit <strong>NIK Pemohon</strong> yang Anda gunakan saat mendaftar.</li>
                            <li>Tekan tombol <strong>"Cari Status"</strong> untuk memunculkan detail status. Unduh PDF surat resmi akan terbuka otomatis setelah disetujui operator.</li>
                        </ol>
                    </div>
                @endif
            @endif
        </div>
    </section>


@endsection

