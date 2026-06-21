@extends('layouts.public')

@section('title', 'Pengajuan ' . $title . ' - ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal'))

@section('content')
    <section class="section" style="padding-top: 48px;">
        <div class="container">
            <div class="section-header" style="margin-bottom: 40px; text-align: left;">
                <a href="{{ route('layanan.index') }}" style="color: var(--text-secondary); display: inline-flex; align-items: center; gap: 6px; margin-bottom: 16px; font-weight: 500;">
                    ⬅ Kembali ke Layanan
                </a>
                <h2 style="font-size: 32px; color: var(--secondary-color);">Formulir Pengajuan</h2>
                <p>Silakan isi informasi berikut untuk mengajukan permohonan berkas <strong>{{ $title }}</strong>.</p>
            </div>

            <!-- Custom Form Card -->
            <div class="card form-card">
                <!-- Stepper Progress Header -->
                <div style="display: flex; justify-content: space-around; border-bottom: 1px solid var(--border-color); padding-bottom: 24px; margin-bottom: 32px;">
                    <div id="step-tab-1" style="font-weight: 700; color: var(--primary-color);" class="step-tab">1. Validasi NIK</div>
                    <div id="step-tab-2" style="font-weight: 600; color: var(--text-light);" class="step-tab">2. Isian Formulir</div>
                    <div id="step-tab-3" style="font-weight: 600; color: var(--text-light);" class="step-tab">3. Unggah Berkas</div>
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

                <!-- Master Form -->
                <form action="{{ route('layanan.form.store', $type) }}" method="POST" enctype="multipart/form-data" id="letterForm">
                    @csrf

                    <!-- STEP 1: VALIDASI NIK -->
                    <div id="step-content-1" class="step-content">
                        <div class="alert alert-success" style="background-color: var(--primary-light); color: var(--primary-color); border-color: rgba(37,99,235,0.1); margin-bottom: 24px;">
                            💡 Masukkan 16 digit NIK Anda. Sistem akan memverifikasi data kependudukan secara otomatis untuk mempermudah pengisian formulir.
                        </div>

                        <div class="form-group">
                            <label for="input_nik">Nomor Induk Kependudukan (NIK)</label>
                            <div style="display: flex; gap: 12px;">
                                <input 
                                    type="text" 
                                    name="nik" 
                                    id="input_nik" 
                                    maxlength="16" 
                                    value="{{ old('nik') }}" 
                                    placeholder="Contoh: 320101XXXXXXXXXX" 
                                    class="form-control"
                                    required
                                >
                                <button type="button" class="btn btn-primary" onclick="verifyNIK()" id="btn-verify">Verifikasi NIK</button>
                            </div>
                            <div id="nik-feedback" style="margin-top: 12px; display: none;"></div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 32px;" id="step-1-nav" style="display: none;">
                            <button type="button" class="btn btn-primary" onclick="goToStep(2)" id="btn-next-1" disabled>Lanjut Ke Langkah 2</button>
                        </div>
                    </div>

                    <!-- STEP 2: FORMULIR DETAIL -->
                    <div id="step-content-2" class="step-content" style="display: none;">
                        <div class="form-group">
                            <label for="input_nama">Nama Lengkap Pemohon</label>
                            <input 
                                type="text" 
                                name="nama_lengkap" 
                                id="input_nama" 
                                value="{{ old('nama_lengkap') }}" 
                                class="form-control" 
                                required
                            >
                        </div>

                        <!-- 2.1 CUSTOM FIELDS: DOMISILI -->
                        @if($type === 'domisili')
                            <div class="form-group">
                                <label for="alamat_domisili">Alamat Domisili yang Diajukan</label>
                                <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" required placeholder="Contoh: Jl. Diponegoro No. 12, RT 01 RW 01, Dusun Krajan">{{ old('alamat_domisili') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="keperluan_domisili">Tujuan / Keperluan Surat</label>
                                <input type="text" name="keperluan" id="keperluan_domisili" value="{{ old('keperluan') }}" class="form-control" placeholder="Contoh: Pengajuan KPR, Buka Rekening Bank, Melamar Kerja" required>
                            </div>
                        @endif

                        <!-- 2.2 CUSTOM FIELDS: SKTM -->
                        @if($type === 'sktm')
                            <div class="form-group">
                                <label for="keperluan_sktm">Tujuan / Keperluan Surat</label>
                                <input type="text" name="keperluan" id="keperluan_sktm" value="{{ old('keperluan') }}" class="form-control" placeholder="Contoh: Keringanan Biaya Rumah Sakit, Beasiswa Sekolah" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nama_sekolah_rs">Nama Instansi Penerima (Sekolah / Rumah Sakit)</label>
                                    <input type="text" name="nama_sekolah_rs" id="nama_sekolah_rs" value="{{ old('nama_sekolah_rs') }}" class="form-control" placeholder="Contoh: SMA Negeri 1 Harapan" required>
                                </div>
                                <div class="form-group">
                                    <label for="penghasilan_orang_tua">Penghasilan Orang Tua / Wali per Bulan (Rupiah)</label>
                                    <input type="number" name="penghasilan_orang_tua" id="penghasilan_orang_tua" value="{{ old('penghasilan_orang_tua') }}" class="form-control" placeholder="Contoh: 1500000" required>
                                </div>
                            </div>
                        @endif

                        <!-- 2.3 CUSTOM FIELDS: PINDAH -->
                        @if($type === 'pindah')
                            <h4 style="font-size: 16px; margin-bottom: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Alamat Daerah Tujuan Pindah</h4>
                            <div class="form-group">
                                <label for="alamat_tujuan">Alamat Lengkap Tujuan</label>
                                <textarea name="alamat_tujuan" id="alamat_tujuan" class="form-control" placeholder="Contoh: Perum Indah Lestari Blok B No. 4, RT 05 RW 09" required>{{ old('alamat_tujuan') }}</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="rt_tujuan">RT Tujuan</label>
                                    <input type="number" name="rt_tujuan" id="rt_tujuan" value="{{ old('rt_tujuan') }}" class="form-control" placeholder="05" required>
                                </div>
                                <div class="form-group">
                                    <label for="rw_tujuan">RW Tujuan</label>
                                    <input type="number" name="rw_tujuan" id="rw_tujuan" value="{{ old('rw_tujuan') }}" class="form-control" placeholder="09" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dusun_tujuan">Dusun / Lingkungan Tujuan</label>
                                    <input type="text" name="dusun_tujuan" id="dusun_tujuan" value="{{ old('dusun_tujuan') }}" class="form-control" placeholder="Dusun Melati" required>
                                </div>
                                <div class="form-group">
                                    <label for="desa_tujuan">Desa / Kelurahan Tujuan</label>
                                    <input type="text" name="desa_tujuan" id="desa_tujuan" value="{{ old('desa_tujuan') }}" class="form-control" placeholder="Desa Asri" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="kecamatan_tujuan">Kecamatan Tujuan</label>
                                    <input type="text" name="kecamatan_tujuan" id="kecamatan_tujuan" value="{{ old('kecamatan_tujuan') }}" class="form-control" placeholder="Kecamatan Indah" required>
                                </div>
                                <div class="form-group">
                                    <label for="kabupaten_tujuan">Kabupaten / Kota Tujuan</label>
                                    <input type="text" name="kabupaten_tujuan" id="kabupaten_tujuan" value="{{ old('kabupaten_tujuan') }}" class="form-control" placeholder="Kabupaten Harmoni" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="provinsi_tujuan">Provinsi Tujuan</label>
                                    <input type="text" name="provinsi_tujuan" id="provinsi_tujuan" value="{{ old('provinsi_tujuan') }}" class="form-control" placeholder="Jawa Barat" required>
                                </div>
                                <div class="form-group">
                                    <label for="alasan_pindah">Alasan Perpindahan</label>
                                    <input type="text" name="alasan_pindah" id="alasan_pindah" value="{{ old('alasan_pindah') }}" class="form-control" placeholder="Contoh: Mengikuti Keluarga, Pekerjaan" required>
                                </div>
                            </div>
                        @endif

                        <div style="display: flex; justify-content: space-between; margin-top: 32px;">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(1)">Kembali</button>
                            <button type="button" class="btn btn-primary" onclick="goToStep(3)">Lanjut Ke Langkah 3</button>
                        </div>
                    </div>

                    <!-- STEP 3: UNGGAH BERKAS -->
                    <div id="step-content-3" class="step-content" style="display: none;">
                        <div class="alert alert-success" style="background-color: var(--primary-light); color: var(--primary-color); border-color: rgba(37,99,235,0.1); margin-bottom: 24px;">
                            📂 Unggah berkas dokumen pendukung Anda (Fotokopi KTP / Kartu Keluarga). Berkas wajib berupa file gambar (JPG, JPEG, PNG), dokumen PDF, atau dokumen Word (DOC, DOCX). Ukuran berkas tidak dibatasi karena sistem akan mengoptimalkan ukurannya secara otomatis.
                        </div>
 
                        <div class="form-group">
                            <label for="berkas_pendukung">Unggah Berkas Pendukung (KTP/KK)</label>
                            <input 
                                type="file" 
                                name="berkas_pendukung" 
                                id="berkas_pendukung" 
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" 
                                class="form-control" 
                                required
                            >
                            <span style="font-size: 11px; color: var(--text-light); margin-top: 4px; display: block;">Format yang diterima: .jpg, .jpeg, .png, .pdf, .doc, .docx (Ukuran bebas, dioptimalkan otomatis oleh sistem)</span>
                        </div>

                        <!-- Checkbox Agreement -->
                        <div class="form-group" style="margin-top: 32px;">
                            <label style="display: flex; align-items: flex-start; gap: 10px; font-weight: 500; cursor: pointer;">
                                <input type="checkbox" required style="margin-top: 4px;">
                                <span style="font-size: 13px; color: var(--text-secondary);">Saya menyatakan bahwa seluruh data yang diisi dan berkas dokumen yang dilampirkan adalah benar, sah, dan dapat dipertanggungjawabkan keasliannya.</span>
                            </label>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 32px;">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(2)">Kembali</button>
                            <button type="submit" class="btn btn-primary">Kirim Permohonan Surat ➔</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    let currentStep = 1;
    const type = "{{ $type }}";

    function verifyNIK() {
        const nik = document.getElementById('input_nik').value;
        const feedback = document.getElementById('nik-feedback');
        const btnVerify = document.getElementById('btn-verify');
        const btnNext1 = document.getElementById('btn-next-1');
        const inputNama = document.getElementById('input_nama');
        const alamatDomisili = document.getElementById('alamat_domisili');

        if (nik.length !== 16 || isNaN(nik)) {
            feedback.style.display = 'block';
            feedback.className = 'alert alert-danger';
            feedback.innerHTML = '⚠️ NIK harus terdiri dari 16 digit angka.';
            return;
        }

        btnVerify.innerHTML = 'Memverifikasi...';
        btnVerify.disabled = true;

        fetch(`{{ route('layanan.check-nik') }}?nik=${nik}`)
            .then(response => response.json())
            .then(data => {
                btnVerify.innerHTML = 'Verifikasi NIK';
                btnVerify.disabled = false;
                feedback.style.display = 'block';

                if (data.success) {
                    feedback.className = 'alert alert-success';
                    feedback.innerHTML = `✅ <strong>NIK Terverifikasi!</strong> Nama warga ditemukan: <strong>${data.resident.nama_lengkap}</strong>. Form otomatis terisi.`;
                    
                    // Autofill nama
                    inputNama.value = data.resident.nama_lengkap;
                    inputNama.readOnly = true;

                    // Autofill alamat jika domisili
                    if (type === 'domisili' && alamatDomisili) {
                        alamatDomisili.value = `${data.resident.alamat}, RT ${data.resident.rt} RW ${data.resident.rw}, Desa Makmur`;
                    }

                    btnNext1.disabled = false;
                } else {
                    feedback.className = 'alert alert-danger';
                    feedback.innerHTML = `⚠️ <strong>Peringatan!</strong> ${data.message}`;
                    
                    // Reset field readOnly
                    inputNama.value = '';
                    inputNama.readOnly = false;
                    
                    btnNext1.disabled = false; // Tetap izinkan lanjut, tetapi manual
                }
            })
            .catch(error => {
                btnVerify.innerHTML = 'Verifikasi NIK';
                btnVerify.disabled = false;
                feedback.style.display = 'block';
                feedback.className = 'alert alert-danger';
                feedback.innerHTML = '⚠️ Terjadi kesalahan koneksi server saat verifikasi NIK.';
            });
    }

    function goToStep(step) {
        // Validation check
        if (step === 2 && currentStep === 1) {
            const nik = document.getElementById('input_nik').value;
            if (nik.length !== 16) {
                alert('Silakan verifikasi NIK Anda terlebih dahulu.');
                return;
            }
        }

        if (step === 3 && currentStep === 2) {
            const inputNama = document.getElementById('input_nama').value;
            if (!inputNama.trim()) {
                alert('Nama lengkap wajib diisi.');
                return;
            }
        }

        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
        // Show current step
        document.getElementById(`step-content-${step}`).style.display = 'block';

        // Update tabs
        document.querySelectorAll('.step-tab').forEach(el => {
            el.style.color = 'var(--text-light)';
            el.style.fontWeight = '500';
        });
        
        const activeTab = document.getElementById(`step-tab-${step}`);
        activeTab.style.color = 'var(--primary-color)';
        activeTab.style.fontWeight = '700';

        currentStep = step;
        window.scrollTo(0, 200);
    }
</script>
@endsection
