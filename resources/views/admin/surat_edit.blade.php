@extends('layouts.admin')

@section('title', 'Edit Pengajuan Surat - ' . $surat->nomor_pengajuan)

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>Edit Pengajuan {{ $surat->nomor_pengajuan }}</h1>
            <p>Perbaiki data biodata pemohon atau berkas formulir pengajuan surat warga.</p>
        </div>
        <div>
            <a href="{{ route('admin.surat.detail', $surat->id) }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <div class="form-card" style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
        <form 
            action="{{ route('admin.surat.update', $surat->id) }}" 
            method="POST"
            data-confirm="Apakah Anda yakin ingin menyimpan perubahan data pengajuan ini?"
            data-confirm-title="Simpan Perubahan Data"
            data-confirm-type="warning"
        >
            @csrf

            <!-- Section 1: Data Utama Pemohon -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px;">Data Utama Pemohon</h3>
            
            <div class="grid-2" style="margin-bottom: 24px;">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap <span style="color:red;">*</span></label>
                    <input 
                        type="text" 
                        name="nama_lengkap" 
                        id="nama_lengkap" 
                        value="{{ old('nama_lengkap', $surat->nama_lengkap) }}" 
                        class="form-control" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="nik">NIK (Nomor Induk Kependudukan) <span style="color:red;">*</span></label>
                    <input 
                        type="text" 
                        name="nik" 
                        id="nik" 
                        value="{{ old('nik', $surat->nik) }}" 
                        class="form-control" 
                        maxlength="16" 
                        required
                    >
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 32px; max-width: 50%;">
                <label for="nomor_surat">Nomor Surat Resmi <span style="color: var(--text-light); font-weight: normal;">(Opsional)</span></label>
                <input 
                    type="text" 
                    name="nomor_surat" 
                    id="nomor_surat" 
                    value="{{ old('nomor_surat', $surat->nomor_surat) }}" 
                    class="form-control" 
                    placeholder="Contoh: 470/005/DSA/VI/2026"
                >
                <span style="font-size: 11px; color: var(--text-light); display: block; margin-top: 4px;">Kosongkan jika nomor surat belum diterbitkan/dihasilkan.</span>
            </div>

            <!-- Section 2: Data Isian Formulir -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px;">
                Data Isian Form — {{ match($surat->jenis_surat) { 'domisili' => 'Surat Keterangan Domisili', 'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)', 'pindah' => 'Surat Pindah Penduduk', default => $surat->jenis_surat } }}
            </h3>

            @if($surat->jenis_surat === 'domisili')
                <!-- FORM ISIAN DOMISILI -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="alamat_domisili">Alamat Domisili <span style="color:red;">*</span></label>
                    <textarea 
                        name="form_data[alamat_domisili]" 
                        id="alamat_domisili" 
                        class="form-control" 
                        rows="3" 
                        required
                    >{{ old('form_data.alamat_domisili', $surat->form_data['alamat_domisili'] ?? '') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="keperluan">Keperluan / Tujuan Pembuatan <span style="color:red;">*</span></label>
                    <input 
                        type="text" 
                        name="form_data[keperluan]" 
                        id="keperluan" 
                        value="{{ old('form_data.keperluan', $surat->form_data['keperluan'] ?? '') }}" 
                        class="form-control" 
                        required
                    >
                </div>

            @elseif($surat->jenis_surat === 'sktm')
                <!-- FORM ISIAN SKTM -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="keperluan">Keperluan / Tujuan Pembuatan <span style="color:red;">*</span></label>
                    <input 
                        type="text" 
                        name="form_data[keperluan]" 
                        id="keperluan" 
                        value="{{ old('form_data.keperluan', $surat->form_data['keperluan'] ?? '') }}" 
                        class="form-control" 
                        required
                    >
                </div>

                <div class="grid-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="nama_sekolah_rs">Nama Sekolah / Instansi / RS <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[nama_sekolah_rs]" 
                            id="nama_sekolah_rs" 
                            value="{{ old('form_data.nama_sekolah_rs', $surat->form_data['nama_sekolah_rs'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="penghasilan_orang_tua">Penghasilan Orang Tua (Rp / Bulan) <span style="color:red;">*</span></label>
                        <input 
                            type="number" 
                            name="form_data[penghasilan_orang_tua]" 
                            id="penghasilan_orang_tua" 
                            value="{{ old('form_data.penghasilan_orang_tua', $surat->form_data['penghasilan_orang_tua'] ?? '') }}" 
                            class="form-control" 
                            min="0" 
                            required
                        >
                    </div>
                </div>

            @elseif($surat->jenis_surat === 'pindah')
                <!-- FORM ISIAN PINDAH -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="alamat_tujuan">Alamat Tujuan Pindah <span style="color:red;">*</span></label>
                    <textarea 
                        name="form_data[alamat_tujuan]" 
                        id="alamat_tujuan" 
                        class="form-control" 
                        rows="3" 
                        required
                    >{{ old('form_data.alamat_tujuan', $surat->form_data['alamat_tujuan'] ?? '') }}</textarea>
                </div>

                <div class="grid-3" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="rt_tujuan">RT Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[rt_tujuan]" 
                            id="rt_tujuan" 
                            value="{{ old('form_data.rt_tujuan', $surat->form_data['rt_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="rw_tujuan">RW Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[rw_tujuan]" 
                            id="rw_tujuan" 
                            value="{{ old('form_data.rw_tujuan', $surat->form_data['rw_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="dusun_tujuan">Dusun Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[dusun_tujuan]" 
                            id="dusun_tujuan" 
                            value="{{ old('form_data.dusun_tujuan', $surat->form_data['dusun_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <div class="grid-3" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="desa_tujuan">Desa/Kelurahan Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[desa_tujuan]" 
                            id="desa_tujuan" 
                            value="{{ old('form_data.desa_tujuan', $surat->form_data['desa_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="kecamatan_tujuan">Kecamatan Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[kecamatan_tujuan]" 
                            id="kecamatan_tujuan" 
                            value="{{ old('form_data.kecamatan_tujuan', $surat->form_data['kecamatan_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="kabupaten_tujuan">Kabupaten/Kota Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[kabupaten_tujuan]" 
                            id="kabupaten_tujuan" 
                            value="{{ old('form_data.kabupaten_tujuan', $surat->form_data['kabupaten_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <div class="grid-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="provinsi_tujuan">Provinsi Tujuan <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[provinsi_tujuan]" 
                            id="provinsi_tujuan" 
                            value="{{ old('form_data.provinsi_tujuan', $surat->form_data['provinsi_tujuan'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="alasan_pindah">Alasan Pindah <span style="color:red;">*</span></label>
                        <input 
                            type="text" 
                            name="form_data[alasan_pindah]" 
                            id="alasan_pindah" 
                            value="{{ old('form_data.alasan_pindah', $surat->form_data['alasan_pindah'] ?? '') }}" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>
            @endif

            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                <a href="{{ route('admin.surat.detail', $surat->id) }}" class="btn btn-secondary" style="flex: 1; text-align: center; justify-content: center; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="flex: 1; background-color: var(--primary-color); display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
