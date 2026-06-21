@extends('layouts.admin')

@section('title', isset($resident) ? 'Edit Data Penduduk' : 'Tambah Penduduk Baru')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>{{ isset($resident) ? 'Ubah Data Penduduk' : 'Tambah Penduduk Baru' }}</h1>
            <p>{{ isset($resident) ? 'Perbarui informasi kependudukan warga desa.' : 'Daftarkan penduduk baru ke database desa untuk validasi layanan dan statistik.' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.content.residents') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <ul style="padding-left: 16px; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form 
            action="{{ isset($resident) ? route('admin.content.residents.update', $resident->nik) : route('admin.content.residents.store') }}" 
            method="POST"
        >
            @csrf
            @if(isset($resident))
                @method('PUT')
            @endif

            <!-- 1. DATA PRIBADI -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px;">Data Pribadi</h3>
            
            <div class="grid-2">
                <div class="form-group">
                    <label for="nik">Nomor Induk Kependudukan (NIK)</label>
                    <input 
                        type="text" 
                        name="nik" 
                        id="nik" 
                        value="{{ old('nik', $resident->nik ?? '') }}" 
                        class="form-control" 
                        placeholder="16 Digit NIK"
                        required
                        maxlength="16"
                        pattern="\d{16}"
                        title="NIK harus berupa 16 digit angka"
                        {{ isset($resident) ? 'readonly style=background-color:#e2e8f0;cursor:not-allowed;' : '' }}
                    >
                    @if(isset($resident))
                        <small style="color: var(--text-light); margin-top: 4px; display: block;">NIK tidak dapat diubah karena merupakan Primary Key kependudukan.</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="nama_lengkap" 
                        id="nama_lengkap" 
                        value="{{ old('nama_lengkap', $resident->nama_lengkap ?? '') }}" 
                        class="form-control" 
                        placeholder="Nama sesuai KTP"
                        required
                    >
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input 
                        type="text" 
                        name="tempat_lahir" 
                        id="tempat_lahir" 
                        value="{{ old('tempat_lahir', $resident->tempat_lahir ?? '') }}" 
                        class="form-control" 
                        placeholder="Kota/Kabupaten Lahir"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input 
                        type="date" 
                        name="tanggal_lahir" 
                        id="tanggal_lahir" 
                        value="{{ old('tanggal_lahir', $resident->tanggal_lahir ?? '') }}" 
                        class="form-control" 
                        required
                    >
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $resident->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $resident->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select name="agama" id="agama" class="form-control" required>
                        <option value="">-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama', $resident->agama ?? '') === $agama ? 'selected' : '' }}>{{ $agama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="pendidikan">Pendidikan Terakhir</label>
                    <select name="pendidikan" id="pendidikan" class="form-control" required>
                        <option value="">-- Pilih Pendidikan --</option>
                        @foreach(['TIDAK / BELUM SEKOLAH', 'BELUM TAMAT SD / SEDERAJAT', 'TAMAT SD / SEDERAJAT', 'SLTP / SEDERAJAT', 'SLTA / SEDERAJAT', 'DIPLOMA I / II', 'AKADEMI / DIPLOMA III / S.MUDA', 'DIPLOMA IV / STRATA I', 'STRATA II', 'STRATA III'] as $edu)
                            <option value="{{ $edu }}" {{ old('pendidikan', $resident->pendidikan ?? '') === $edu ? 'selected' : '' }}>{{ $edu }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input 
                        type="text" 
                        name="pekerjaan" 
                        id="pekerjaan" 
                        value="{{ old('pekerjaan', $resident->pekerjaan ?? '') }}" 
                        class="form-control" 
                        placeholder="Contoh: PETANI, WIRASWASTA, PNS, dll"
                        required
                    >
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="status_perkawinan">Status Perkawinan</label>
                    <select name="status_perkawinan" id="status_perkawinan" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach(['BELUM KAWIN', 'KAWIN', 'CERAI HIDUP', 'CERAI MATI'] as $status)
                            <option value="{{ $status }}" {{ old('status_perkawinan', $resident->status_perkawinan ?? '') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="bantuan_sosial">Penerima Bantuan Sosial</label>
                    <select name="bantuan_sosial" id="bantuan_sosial" class="form-control" required>
                        @foreach(['Tidak Ada', 'PKH', 'BPNT', 'BST', 'BLT Dana Desa'] as $bansos)
                            <option value="{{ $bansos }}" {{ old('bantuan_sosial', $resident->bantuan_sosial ?? 'Tidak Ada') === $bansos ? 'selected' : '' }}>{{ $bansos }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 2. ALAMAT & TEMPAT TINGGAL -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px; margin-top: 32px;">Alamat & Tempat Tinggal</h3>
            
            <div class="form-group">
                <label for="alamat">Alamat Jalan / Dusun</label>
                <textarea 
                    name="alamat" 
                    id="alamat" 
                    class="form-control" 
                    style="min-height: 80px;" 
                    placeholder="Nama jalan, RT/RW, Dusun"
                    required
                >{{ old('alamat', $resident->alamat ?? '') }}</textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="rt">Rukun Tetangga (RT)</label>
                    <input 
                        type="text" 
                        name="rt" 
                        id="rt" 
                        value="{{ old('rt', $resident->rt ?? '') }}" 
                        class="form-control" 
                        placeholder="Contoh: 001"
                        required
                        maxlength="5"
                    >
                </div>

                <div class="form-group">
                    <label for="rw">Rukun Warga (RW)</label>
                    <input 
                        type="text" 
                        name="rw" 
                        id="rw" 
                        value="{{ old('rw', $resident->rw ?? '') }}" 
                        class="form-control" 
                        placeholder="Contoh: 002"
                        required
                        maxlength="5"
                    >
                </div>
            </div>

            <!-- 3. EKONOMI & UMKM -->
            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px; margin-top: 32px;">Informasi Tambahan (UMKM)</h3>
            
            <div class="form-group">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <input 
                        type="checkbox" 
                        name="is_umkm" 
                        id="is_umkm" 
                        value="1" 
                        {{ old('is_umkm', $resident->is_umkm ?? false) ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer;"
                    >
                    <label for="is_umkm" style="margin-bottom: 0; cursor: pointer; font-weight: 600;">Warga ini merupakan Pelaku UMKM</label>
                </div>
            </div>

            <div class="form-group" id="umkm_name_group" style="display: {{ old('is_umkm', $resident->is_umkm ?? false) ? 'block' : 'none' }};">
                <label for="nama_umkm">Nama Usaha / UMKM</label>
                <input 
                    type="text" 
                    name="nama_umkm" 
                    id="nama_umkm" 
                    value="{{ old('nama_umkm', $resident->nama_umkm ?? '') }}" 
                    class="form-control" 
                    placeholder="Contoh: Toko Kelontong Barokah, Warung Bakso"
                >
            </div>

            <!-- SUBMIT BUTTONS -->
            <div style="margin-top: 40px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('admin.content.residents') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    {{ isset($resident) ? 'Perbarui Data' : 'Simpan Data Penduduk' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isUmkmCheckbox = document.getElementById('is_umkm');
            const umkmNameGroup = document.getElementById('umkm_name_group');
            const umkmNameInput = document.getElementById('nama_umkm');

            function toggleUmkmGroup() {
                if (isUmkmCheckbox.checked) {
                    umkmNameGroup.style.display = 'block';
                    umkmNameInput.setAttribute('required', 'required');
                } else {
                    umkmNameGroup.style.display = 'none';
                    umkmNameInput.removeAttribute('required');
                }
            }

            isUmkmCheckbox.addEventListener('change', toggleUmkmGroup);
            
            // Trigger on page load in case of validation back-flash or edit page load
            toggleUmkmGroup();
        });
    </script>
@endsection
