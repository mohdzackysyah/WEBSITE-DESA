<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Resident;
use App\Models\Setting;
use App\Models\Post;
use App\Models\Gallery;
use App\Models\OrganizationMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Operators & Admin)
        User::create([
            'name' => 'Operator Pelayanan',
            'email' => 'pelayanan@desa.go.id',
            'role' => 'operator_pelayanan',
            'password' => Hash::make('desa123'),
        ]);

        User::create([
            'name' => 'Operator Konten',
            'email' => 'konten@desa.go.id',
            'role' => 'operator_konten',
            'password' => Hash::make('desa123'),
        ]);

        User::create([
            'name' => 'Admin Desa',
            'email' => 'admin@desa.go.id',
            'role' => 'admin',
            'password' => Hash::make('desa123'),
        ]);

        // 2. Seed Settings (Profil Desa)
        $settings = [
            'nama_desa' => 'Desa Penebal',
            'kecamatan' => 'Kecamatan Bengkalis',
            'kabupaten' => 'Kabupaten Bengkalis',
            'sejarah' => 'Desa Penebal merupakan salah satu desa yang terletak di Kecamatan Bengkalis, Kabupaten Bengkalis, Provinsi Riau. Desa ini memiliki sejarah panjang yang kaya akan nilai-nilai kebudayaan Melayu yang kental, kebersamaan, dan semangat gotong royong warga yang tinggi. Bermula sebagai pemukiman pesisir yang strategis, Desa Penebal kini terus bertransformasi menuju desa mandiri dengan mengoptimalkan sektor perkebunan karet, pertanian, perikanan kelautan, serta integrasi teknologi informasi dalam tata kelola pelayanan publik.',
            'sambutan_kepala' => 'Selamat datang di Website Resmi Desa Penebal. Melalui platform digital ini, kami berkomitmen untuk menghadirkan pelayanan administrasi publik yang cepat, transparan, akuntabel, dan modern bagi seluruh warga. Kami berharap warga Desa Penebal dapat mengurus dokumen kependudukan secara praktis secara mandiri online dari rumah. Mari kita bersama-sama bersinergi mewujudkan Desa Penebal yang maju, sejahtera, dan mandiri.',
            'nama_kepala' => 'M. Sani',
            'visi' => 'Terwujudnya Desa Penebal yang Maju, Sejahtera, Mandiri, dan Unggul dalam Pelayanan Publik Berbasis Teknologi Informasi.',
            'misi' => "1. Meningkatkan kualitas pelayanan administrasi desa melalui sistem digital yang terintegrasi dan responsif.\n2. Mendorong perekonomian warga melalui optimalisasi UMKM lokal, pertanian, dan perikanan berbasis potensi lokal.\n3. Mewujudkan tata kelola pemerintahan desa yang bersih, transparan, partisipatif, dan akuntabel.\n4. Meningkatkan kualitas infrastruktur dasar pedesaan serta pelestarian lingkungan hidup secara berkelanjutan.",
            'alamat' => 'Jl. Utama Desa Penebal, Kecamatan Bengkalis, Kabupaten Bengkalis, Riau, 28711',
            'kontak_telepon' => '0812-7566-2910',
            'kontak_email' => 'info@penebal.desa.id',
            'jam_pelayanan' => "Senin - Kamis: 08:00 - 14:00 WIB\nJumat: 08:00 - 11:30 WIB\nSabtu - Minggu: Libur (Tutup)",
            'peta_lokasi' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127641.1685375549!2d102.08323675000001!3d1.4820986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1676dfb37c041%3A0xea8dc2ccdf5349f7!2sBengkalis%20Island!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 3. Seed 35 Residents (Demographics for Chart.js and NIK Verification)
        $residentsData = [
            // NIK, Nama Lengkap, Tempat Lahir, Tgl Lahir, Gender, Agama, Pekerjaan, Alamat, RT, RW, Pendidikan, Status Nikah, Bansos, IsUMKM, NamaUMKM
            ['3201011203750001', 'Budi Santoso', 'Bogor', '1975-03-12', 'L', 'Islam', 'Petani', 'Rt 01 Rw 01 Dusun Krajan', '01', '01', 'SD', 'Kawin', 'Tidak Ada', false, null],
            ['3201015607800002', 'Siti Aminah', 'Bandung', '1980-07-16', 'P', 'Islam', 'Ibu Rumah Tangga', 'Rt 01 Rw 01 Dusun Krajan', '01', '01', 'SMP', 'Kawin', 'PKH', false, null],
            ['3201012210020003', 'Rian Hidayat', 'Sejahtera', '2002-10-22', 'L', 'Islam', 'Karyawan Swasta', 'Rt 01 Rw 01 Dusun Krajan', '01', '01', 'SMA', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201010101650004', 'Wayan Sudarta', 'Denpasar', '1965-01-01', 'L', 'Hindu', 'Wiraswasta', 'Rt 02 Rw 01 Dusun Krajan', '02', '01', 'Sarjana', 'Kawin', 'Tidak Ada', true, 'Kopi Bubuk Wirya'],
            ['3201014502700005', 'Ni Made Kartini', 'Gianyar', '1970-02-05', 'P', 'Hindu', 'Wiraswasta', 'Rt 02 Rw 01 Dusun Krajan', '02', '01', 'SMA', 'Kawin', 'Tidak Ada', true, 'Kripik Pisang Bali'],
            ['3201011508880006', 'Joko Susilo', 'Solo', '1988-08-15', 'L', 'Islam', 'PNS', 'Rt 02 Rw 01 Dusun Krajan', '02', '01', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201014811900007', 'Sri Wahyuni', 'Yogyakarta', '1990-11-08', 'P', 'Islam', 'Karyawan Swasta', 'Rt 02 Rw 01 Dusun Krajan', '02', '01', 'Diploma', 'Kawin', 'Tidak Ada', false, null],
            ['3201012512950008', 'Ahmad Fauzi', 'Surabaya', '1995-12-25', 'L', 'Islam', 'Wiraswasta', 'Rt 03 Rw 01 Dusun Krajan', '03', '01', 'SMA', 'Belum Kawin', 'Tidak Ada', true, 'Bengkel Makmur Motor'],
            ['3201016204010009', 'Lani Wijaya', 'Semarang', '2001-04-22', 'P', 'Buddha', 'Pelajar/Mahasiswa', 'Rt 03 Rw 01 Dusun Krajan', '03', '01', 'Sarjana', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201010505500010', 'Suwarno', 'Klaten', '1950-05-05', 'L', 'Islam', 'Belum/Tidak Bekerja', 'Rt 03 Rw 01 Dusun Krajan', '03', '01', 'SD', 'Cerai Mati', 'BPNT', false, null],
            ['3201011111600011', 'Robertus Siregar', 'Medan', '1960-11-11', 'L', 'Kristen', 'Wiraswasta', 'Rt 01 Rw 02 Dusun Wana', '01', '02', 'Sarjana', 'Kawin', 'Tidak Ada', true, 'Toko Kelontong Siregar'],
            ['3201015208650012', 'Maria Christina', 'Manado', '1965-08-12', 'P', 'Katolik', 'PNS', 'Rt 01 Rw 02 Dusun Wana', '01', '02', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201012002050013', 'Daniel Siregar', 'Sejahtera', '2005-02-20', 'L', 'Kristen', 'Pelajar/Mahasiswa', 'Rt 01 Rw 02 Dusun Wana', '01', '02', 'SMA', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201015006450014', 'Mbah Sutini', 'Sejahtera', '1945-06-10', 'P', 'Islam', 'Belum/Tidak Bekerja', 'Rt 02 Rw 02 Dusun Wana', '02', '02', 'Tidak Sekolah', 'Cerai Mati', 'PKH', false, null],
            ['3201010408780015', 'Tono Wijaya', 'Cirebon', '1978-08-04', 'L', 'Islam', 'Buruh Tani', 'Rt 02 Rw 02 Dusun Wana', '02', '02', 'SMP', 'Kawin', 'BPNT', false, null],
            ['3201014209820016', 'Sumarni', 'Sejahtera', '1982-09-02', 'P', 'Islam', 'Buruh Tani', 'Rt 02 Rw 02 Dusun Wana', '02', '02', 'SD', 'Kawin', 'BPNT', false, null],
            ['3201010610990017', 'Eko Prasetyo', 'Sejahtera', '1999-10-06', 'L', 'Islam', 'Karyawan Swasta', 'Rt 03 Rw 02 Dusun Wana', '03', '02', 'SMA', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201014101020018', 'Dewi Lestari', 'Sejahtera', '2002-01-01', 'P', 'Islam', 'Karyawan Swasta', 'Rt 03 Rw 02 Dusun Wana', '03', '02', 'SMA', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201011804720019', 'Rudi Hermawan', 'Bandung', '1972-04-18', 'L', 'Islam', 'Wiraswasta', 'Rt 01 Rw 03 Dusun Bukit', '01', '03', 'SMA', 'Kawin', 'Tidak Ada', true, 'Kerajinan Bambu Lestari'],
            ['3201015905750020', 'Rita Sugiharto', 'Purwokerto', '1975-05-19', 'P', 'Islam', 'Ibu Rumah Tangga', 'Rt 01 Rw 03 Dusun Bukit', '01', '03', 'SMP', 'Kawin', 'Tidak Ada', false, null],
            ['3201012903980021', 'Agus Dermawan', 'Sejahtera', '1998-03-29', 'L', 'Islam', 'Buruh Tani', 'Rt 01 Rw 03 Dusun Bukit', '01', '03', 'SMA', 'Belum Kawin', 'BST', false, null],
            ['3201010808800022', 'Andi Wijaya', 'Malang', '1980-08-08', 'L', 'Katolik', 'Karyawan Swasta', 'Rt 02 Rw 03 Dusun Bukit', '02', '03', 'Diploma', 'Kawin', 'Tidak Ada', false, null],
            ['3201014808830023', 'Lucia Natalia', 'Surabaya', '1983-08-18', 'P', 'Katolik', 'Karyawan Swasta', 'Rt 02 Rw 03 Dusun Bukit', '02', '03', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201010203770024', 'Mulyono', 'Sejahtera', '1977-03-02', 'L', 'Islam', 'Petani', 'Rt 03 Rw 03 Dusun Bukit', '03', '03', 'SMP', 'Kawin', 'Tidak Ada', false, null],
            ['3201014404800025', 'Suprihatin', 'Sejahtera', '1980-04-04', 'P', 'Islam', 'Petani', 'Rt 03 Rw 03 Dusun Bukit', '03', '03', 'SD', 'Kawin', 'Tidak Ada', false, null],
            ['3201011505040026', 'Fajar Mulyono', 'Sejahtera', '2004-05-15', 'L', 'Islam', 'Pelajar/Mahasiswa', 'Rt 03 Rw 03 Dusun Bukit', '03', '03', 'SMA', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201011409600027', 'Hendrawan', 'Jakarta', '1960-09-14', 'L', 'Kristen', 'Pensiunan', 'Rt 01 Rw 04 Dusun Indah', '01', '04', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201015409630028', 'Christina Natalia', 'Jakarta', '1963-09-24', 'P', 'Kristen', 'Ibu Rumah Tangga', 'Rt 01 Rw 04 Dusun Indah', '01', '04', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201012301900029', 'Hendra Kusuma', 'Sejahtera', '1990-01-23', 'L', 'Islam', 'Wiraswasta', 'Rt 02 Rw 04 Dusun Indah', '02', '04', 'Sarjana', 'Kawin', 'Tidak Ada', true, 'Digital Printing Makmur'],
            ['3201016301930030', 'Indah Permata', 'Bandung', '1993-01-03', 'P', 'Islam', 'Karyawan Swasta', 'Rt 02 Rw 04 Dusun Indah', '02', '04', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201010303680031', 'Kadek Adnyana', 'Denpasar', '1968-03-03', 'L', 'Hindu', 'PNS', 'Rt 03 Rw 04 Dusun Indah', '03', '04', 'Sarjana', 'Kawin', 'Tidak Ada', false, null],
            ['3201014303720032', 'Ni Ketut Sunari', 'Gianyar', '1972-03-13', 'P', 'Hindu', 'Ibu Rumah Tangga', 'Rt 03 Rw 04 Dusun Indah', '03', '04', 'SMA', 'Kawin', 'Tidak Ada', false, null],
            ['3201012112000033', 'I Putu Gede', 'Sejahtera', '2000-12-21', 'L', 'Hindu', 'Pelajar/Mahasiswa', 'Rt 03 Rw 04 Dusun Indah', '03', '04', 'SMA', 'Belum Kawin', 'Tidak Ada', false, null],
            ['3201010909550034', 'Sulaeman', 'Cianjur', '1955-09-09', 'L', 'Islam', 'Belum/Tidak Bekerja', 'Rt 01 Rw 01 Dusun Krajan', '01', '01', 'SD', 'Kawin', 'BST', false, null],
            ['3201014909580035', 'Aminah Sulaeman', 'Sejahtera', '1958-09-19', 'P', 'Islam', 'Petani', 'Rt 01 Rw 01 Dusun Krajan', '01', '01', 'SD', 'Kawin', 'BST', false, null],
        ];

        foreach ($residentsData as $r) {
            Resident::create([
                'nik' => $r[0],
                'nama_lengkap' => $r[1],
                'tempat_lahir' => $r[2],
                'tanggal_lahir' => $r[3],
                'jenis_kelamin' => $r[4],
                'agama' => $r[5],
                'pekerjaan' => $r[6],
                'alamat' => $r[7],
                'rt' => $r[8],
                'rw' => $r[9],
                'pendidikan' => $r[10],
                'status_perkawinan' => $r[11],
                'bantuan_sosial' => $r[12],
                'is_umkm' => $r[13],
                'nama_umkm' => $r[14],
            ]);
        }

        // 4. Seed Posts (Berita & Informasi Desa)
        Post::create([
            'title' => 'Desa Penebal Luncurkan Layanan Administrasi Surat Online Mandiri',
            'content' => '<p>Hari ini Pemerintah Desa Penebal secara resmi meluncurkan portal website desa terpadu untuk pengajuan surat administrasi warga secara online. Melalui sistem ini, warga tidak perlu lagi mengantre lama di balai desa. Cukup memasukkan NIK dan mengisi formulir dari rumah, surat akan diproses oleh operator pelayanan secara digital. Pengajuan ini mencakup Surat Keterangan Domisili, SKTM, dan Surat Pindah Penduduk.</p><p>Kepala Desa Penebal, M. Sani, menyatakan bahwa langkah ini adalah tonggak sejarah penting untuk mewujudkan tata kelola desa berbasis teknologi. Program ini juga didukung dengan sistem penomoran otomatis serta integrasi basis data kependudukan lokal demi meminimalisasi kesalahan ketik.</p>',
            'image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=800',
            'status' => 'published',
        ]);

        Post::create([
            'title' => 'Pemberdayaan UMKM Sagu dan Produk Perikanan Desa Penebal Bengkalis',
            'content' => '<p>Kelompok UMKM pengolah sagu dan hasil perikanan laut di Desa Penebal berhasil mengembangkan pangsa pasarnya hingga ke luar pulau Bengkalis. Melalui bimbingan dinas koperasi dan UKM, para pelaku usaha dibekali dengan pelatihan pengemasan produk yang higienis serta pemasaran digital.</p><p>Dengan hadirnya platform digital desa ini, profil UMKM lokal kini ditayangkan pada halaman publik, memudahkan para pembeli dan pelancong luar daerah menemukan produk unggulan khas Desa Penebal seperti lempuk sagu dan terasi khas Bengkalis.</p>',
            'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?q=80&w=800',
            'status' => 'published',
        ]);

        Post::create([
            'title' => 'Gotong Royong Akbar Perbaikan Dermaga Nelayan Pesisir Desa Penebal',
            'content' => '<p>Warga Desa Penebal khususnya kelompok nelayan pesisir secara serentak melaksanakan gotong royong kerja bakti untuk merapikan dan memperbaiki dermaga sandar kapal nelayan yang mengalami kerusakan ringan. Perbaikan dermaga ini penting demi kelancaran aktivitas bongkar muat hasil tangkapan ikan harian nelayan.</p><p>Kepala Desa Penebal mengapresiasi kebersamaan warga dan menegaskan komitmen desa untuk terus meremajakan infrastruktur dasar penunjang sektor perikanan yang menjadi mata pencaharian utama sebagian warga pesisir.</p>',
            'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=800',
            'status' => 'published',
        ]);

        // 5. Seed Galleries
        $galleriesData = [
            ['Rapat Sosialisasi Digitalisasi Pelayanan Desa Penebal', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=800', 'kegiatan'],
            ['Panen Hasil Kebun Sagu Kelompok Tani Penebal', 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=800', 'kegiatan'],
            ['Pameran Produk Makanan Olahan Sagu Lokal', 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?q=80&w=800', 'kegiatan'],
            ['Pembangunan Jembatan Penghubung Antar Dusun Penebal', 'https://images.unsplash.com/photo-1596436889106-be35e843f974?q=80&w=800', 'pembangunan'],
            ['Fasilitas Posyandu Ramah Anak Desa Penebal', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800', 'fasilitas'],
            ['Kantor Balai Desa Penebal', 'https://images.unsplash.com/photo-1596436889106-be35e843f974?q=80&w=800', 'fasilitas'],
        ];

        foreach ($galleriesData as $g) {
            Gallery::create([
                'title' => $g[0],
                'image' => $g[1],
                'category' => $g[2],
            ]);
        }

        // 6. Seed Organization Members
        $members = [
            ['name' => 'M. Sani', 'position' => 'Kepala Desa', 'level' => 1, 'description' => 'Pimpinan tertinggi jalannya roda pemerintahan dan pelayanan di Desa Penebal.', 'sort_order' => 1],
            ['name' => 'Zulkifli', 'position' => 'Sekretaris Desa', 'level' => 2, 'description' => 'Koordinator administrasi umum, pelaporan keuangan, dan penyusunan perencanaan desa.', 'sort_order' => 2],
            ['name' => 'Budi Santoso', 'position' => 'Kasi Pemerintahan', 'level' => 3, 'description' => 'Mengelola administrasi pertanahan, kependudukan, ketertiban umum, dan pembinaan wilayah.', 'sort_order' => 3],
            ['name' => 'Sri Mulyani', 'position' => 'Kasi Kesejahteraan', 'level' => 3, 'description' => 'Mengelola program bantuan sosial, pembangunan infrastruktur, pembinaan keagamaan & PAUD.', 'sort_order' => 4],
            ['name' => 'Lani Wijaya', 'position' => 'Kasi Pelayanan', 'level' => 3, 'description' => 'Mengurus pelayanan persuratan, program jaminan kesehatan, dan pemberdayaan masyarakat.', 'sort_order' => 5],
            ['name' => 'Joko Susilo', 'position' => 'Kaur Umum & TU', 'level' => 3, 'description' => 'Bertanggung jawab pada surat menyurat, pengarsipan berkas, kepegawaian, dan aset kantor desa.', 'sort_order' => 6],
            ['name' => 'Sri Wahyuni', 'position' => 'Kaur Keuangan', 'level' => 3, 'description' => 'Mengelola penganggaran, pembukuan kas desa, transaksi pembayaran belanja, dan perpajakan.', 'sort_order' => 7],
            ['name' => 'Rian Hidayat', 'position' => 'Kaur Perencanaan', 'level' => 3, 'description' => 'Menyusun RKPDesa, APBDesa, laporan pertanggungjawaban desa, serta perencanaan program jangka panjang.', 'sort_order' => 8],
            ['name' => 'Ahmad Fauzi', 'position' => 'Kepala Dusun Krajan', 'level' => 4, 'description' => 'Pembina wilayah RW 01 yang membawahi administrasi kependudukan dan kerukunan warga lokal.', 'sort_order' => 9],
            ['name' => 'Tono Wijaya', 'position' => 'Kepala Dusun Wana', 'level' => 4, 'description' => 'Pembina wilayah RW 02, berfokus pada kelancaran aktivitas pertanian dan gotong royong dusun.', 'sort_order' => 10],
            ['name' => 'Rudi Hermawan', 'position' => 'Kepala Dusun Bukit', 'level' => 4, 'description' => 'Pembina wilayah RW 03, mengkoordinir kelompok UMKM kerajinan serta pelaporan warga baru.', 'sort_order' => 11],
            ['name' => 'Hendra Kusuma', 'position' => 'Kepala Dusun Indah', 'level' => 4, 'description' => 'Pembina wilayah RW 04, membina keamanan swakarsa warga dan pembangunan parit/jalan dusun.', 'sort_order' => 12],
        ];

        foreach ($members as $m) {
            OrganizationMember::create($m);
        }

        // 7. Seed Letter Templates
        $this->call(LetterTemplateSeeder::class);
    }
}
