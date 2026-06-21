<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Resmi - {{ $surat->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #000;
            line-height: 1.5;
            padding: 20px 30px;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .kop-surat h1 {
            margin: 4px 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-surat p {
            margin: 0;
            font-size: 12px;
            font-style: italic;
        }
        .judul-surat {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 25px;
        }
        .judul-surat h3 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-surat p {
            margin: 4px 0 0 0;
            font-family: monospace;
            font-size: 13px;
        }
        .isi-surat {
            text-align: justify;
            margin-bottom: 20px;
        }
        .tabel-data {
            width: 100%;
            margin: 20px 0;
            margin-left: 20px;
            border-collapse: collapse;
        }
        .tabel-data td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .tabel-data td.label {
            width: 180px;
        }
        .tabel-data td.colon {
            width: 10px;
        }
        .penutup {
            margin-top: 30px;
            margin-bottom: 50px;
        }
        .tanda-tangan {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 20px;
        }
        .tanda-tangan .jabatan {
            margin-bottom: 70px;
        }
        .tanda-tangan .nama {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Resmi -->
    <div class="kop-surat">
        <h2>Pemerintah {{ $kabupaten }}</h2>
        <h2>Kecamatan {{ $kecamatan }}</h2>
        <h1>Kantor Kepala Desa {{ $namaDesa }}</h1>
        <p>Alamat: {{ \App\Models\Setting::get('alamat') }} | Email: {{ \App\Models\Setting::get('kontak_email') }}</p>
    </div>

    <!-- Judul Surat -->
    <div class="judul-surat">
        <h3>
            {{ match($surat->jenis_surat) {
                'domisili' => 'Surat Keterangan Domisili',
                'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'pindah' => 'Surat Keterangan Pindah',
                default => 'Surat Keterangan'
            } }}
        </h3>
        <p>Nomor: {{ $surat->nomor_surat }}</p>
    </div>

    <!-- Pembuka -->
    <div class="isi-surat">
        Yang bertanda tangan di bawah ini, Kepala Desa {{ $namaDesa }}, Kecamatan {{ $kecamatan }}, {{ $kabupaten }}, menerangkan dengan sebenarnya bahwa:
    </div>

    <!-- Data Warga -->
    <table class="tabel-data">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td><strong>{{ $surat->nama_lengkap }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="colon">:</td>
            <td>{{ $surat->nik }}</td>
        </tr>
        <tr>
            <td class="label">Tempat / Tanggal Lahir</td>
            <td class="colon">:</td>
            <td>
                {{ $resident ? $resident->tempat_lahir : 'Bogor' }}, 
                {{ \Carbon\Carbon::parse($resident ? $resident->tanggal_lahir : now())->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="colon">:</td>
            <td>
                @if($resident)
                    {{ $resident->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                @else
                    Laki-Laki
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Agama</td>
            <td class="colon">:</td>
            <td>{{ $resident ? $resident->agama : 'Islam' }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="colon">:</td>
            <td>{{ $resident ? $resident->pekerjaan : 'Petani' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Asal</td>
            <td class="colon">:</td>
            <td>{{ $resident ? $resident->alamat : 'Rt 01 Rw 01 Dusun Krajan, Desa Makmur' }}</td>
        </tr>
    </table>

    <!-- Isi Detail Berdasarkan Tipe Surat -->
    <div class="isi-surat">
        @if($surat->jenis_surat === 'domisili')
            Menerangkan bahwa nama tersebut di atas adalah benar-benar warga kami yang berdomisili menetap di: <strong>{{ $surat->form_data['alamat_domisili'] ?? '-' }}</strong>.
            <br><br>
            Surat keterangan ini diberikan kepada yang bersangkutan untuk dipergunakan sebagai kelengkapan berkas/persyaratan: <strong>{{ $surat->form_data['keperluan'] ?? '-' }}</strong>.
            
        @elseif($surat->jenis_surat === 'sktm')
            Menerangkan bahwa keluarga nama tersebut di atas adalah benar warga Desa {{ $namaDesa }} yang berdasarkan peninjauan kami tergolong dalam kategori <strong>Keluarga Kurang Mampu (Pra-Sejahtera)</strong>.
            <br><br>
            Surat keterangan tidak mampu ini diberikan sebagai kelengkapan administrasi/persyaratan: <strong>{{ $surat->form_data['keperluan'] ?? '-' }}</strong> pada instansi <strong>{{ $surat->form_data['nama_sekolah_rs'] ?? '-' }}</strong>.
            
        @elseif($surat->jenis_surat === 'pindah')
            Menerangkan bahwa nama tersebut di atas telah mengajukan permohonan surat pindah penduduk keluar dari Desa {{ $namaDesa }} menuju alamat baru sebagai berikut:
            <br>
            <table style="width: 100%; margin-left: 20px; font-size: 13px; margin-top: 10px;">
                <tr>
                    <td style="width: 140px;">Alamat Tujuan</td>
                    <td style="width: 10px;">:</td>
                    <td>{{ $surat->form_data['alamat_tujuan'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>RT/RW Tujuan</td>
                    <td>:</td>
                    <td>RT {{ $surat->form_data['rt_tujuan'] ?? '-' }} / RW {{ $surat->form_data['rw_tujuan'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Wilayah Tujuan</td>
                    <td>:</td>
                    <td>Dusun {{ $surat->form_data['dusun_tujuan'] ?? '-' }}, Desa {{ $surat->form_data['desa_tujuan'] ?? '-' }}, Kec. {{ $surat->form_data['kecamatan_tujuan'] ?? '-' }}, Kab. {{ $surat->form_data['kabupaten_tujuan'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Provinsi Tujuan</td>
                    <td>:</td>
                    <td>{{ $surat->form_data['provinsi_tujuan'] ?? '-' }}</td>
                </tr>
            </table>
            <br>
            Adapun alasan perpindahan penduduk tersebut adalah disebabkan oleh: <strong>{{ $surat->form_data['alasan_pindah'] ?? '-' }}</strong>.
        @endif
    </div>

    <!-- Penutup -->
    <div class="penutup">
        Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <!-- Tanda Tangan -->
    <div class="tanda-tangan">
        <div class="tanggal">{{ $namaDesa }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        <div class="jabatan">Kepala Desa {{ $namaDesa }}</div>
        <div class="nama">{{ $namaKepala }}</div>
    </div>

</body>
</html>
