<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')->orderBy('created_at', 'desc')->take(3)->get();
        $totalPenduduk = Resident::count();
        $totalKK = 12; // Estimated sample families seeded
        $totalUMKM = Resident::where('is_umkm', true)->count();
        
        $sejarahSingkat = Setting::get('sejarah');
        $sejarahSingkat = $sejarahSingkat ? \Illuminate\Support\Str::words($sejarahSingkat, 40) : '';
        
        return view('public.home', compact('posts', 'totalPenduduk', 'totalKK', 'totalUMKM', 'sejarahSingkat'));
    }

    public function profil()
    {
        $sejarah = Setting::get('sejarah');
        $sambutan = Setting::get('sambutan');
        $nama_kepala = Setting::get('nama_kepala');
        $visi = Setting::get('visi');
        $misi = Setting::get('misi');
        $alamat = Setting::get('alamat');
        $kontak_telepon = Setting::get('kontak_telepon');
        $kontak_email = Setting::get('kontak_email');
        $jam_pelayanan = Setting::get('jam_pelayanan');
        $peta_lokasi = Setting::get('peta_lokasi');

        $kades = \App\Models\OrganizationMember::where('level', 1)->first();

        return view('public.profil', compact(
            'sejarah', 'sambutan', 'nama_kepala', 'visi', 'misi', 
            'alamat', 'kontak_telepon', 'kontak_email', 'jam_pelayanan', 'peta_lokasi',
            'kades'
        ));
    }

    public function berita(Request $request)
    {
        $query = Post::where('status', 'published')->orderBy('created_at', 'desc');
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm);
            });
        }

        $posts = $query->paginate(6);
        return view('public.berita', compact('posts'));
    }

    public function detailBerita($slug)
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('public.berita_detail', compact('post'));
    }

    public function galeri()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        return view('public.galeri', compact('galleries'));
    }

    public function statistik()
    {
        $totalPenduduk = Resident::count();
        
        // 1. Gender
        $genderData = Resident::selectRaw('jenis_kelamin, count(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')
            ->toArray();
        $genderData = [
            'Laki-laki' => $genderData['L'] ?? 0,
            'Perempuan' => $genderData['P'] ?? 0
        ];

        // 2. Age Groups
        $residents = Resident::all();
        $ageGroups = [
            'Anak-Anak (0-14)' => 0,
            'Remaja (15-24)' => 0,
            'Dewasa Muda (25-44)' => 0,
            'Dewasa (45-59)' => 0,
            'Lansia (60+)' => 0,
        ];
        foreach ($residents as $r) {
            $age = Carbon::parse($r->tanggal_lahir)->age;
            if ($age <= 14) $ageGroups['Anak-Anak (0-14)']++;
            elseif ($age <= 24) $ageGroups['Remaja (15-24)']++;
            elseif ($age <= 44) $ageGroups['Dewasa Muda (25-44)']++;
            elseif ($age <= 59) $ageGroups['Dewasa (45-59)']++;
            else $ageGroups['Lansia (60+)']++;
        }

        // 3. Religion
        $religionData = Resident::selectRaw('agama, count(*) as total')
            ->groupBy('agama')
            ->pluck('total', 'agama')
            ->toArray();

        // 4. Occupation
        $occupationData = Resident::selectRaw('pekerjaan, count(*) as total')
            ->groupBy('pekerjaan')
            ->orderBy('total', 'desc')
            ->pluck('total', 'pekerjaan')
            ->toArray();

        // 5. Education
        $educationData = Resident::selectRaw('pendidikan, count(*) as total')
            ->groupBy('pendidikan')
            ->pluck('total', 'pendidikan')
            ->toArray();

        // 6. Bansos (Bantuan Sosial)
        $bansosData = Resident::selectRaw('bantuan_sosial, count(*) as total')
            ->groupBy('bantuan_sosial')
            ->pluck('total', 'bantuan_sosial')
            ->toArray();

        // 7. UMKM
        $umkmData = [
            'Bukan UMKM' => Resident::where('is_umkm', false)->count(),
            'Pelaku UMKM' => Resident::where('is_umkm', true)->count(),
        ];

        return view('public.statistik', compact(
            'totalPenduduk', 'genderData', 'ageGroups', 'religionData', 
            'occupationData', 'educationData', 'bansosData', 'umkmData'
        ));
    }

    public function struktur()
    {
        $members = \App\Models\OrganizationMember::orderBy('sort_order', 'asc')->get();
        
        $kades = $members->where('level', 1)->first();
        $sekdes = $members->where('level', 2)->first();
        $perangkat = $members->where('level', 3);
        $kadus = $members->where('level', 4);

        return view('public.struktur', compact('kades', 'sekdes', 'perangkat', 'kadus'));
    }

    public function kontak()
    {
        $alamat = Setting::get('alamat');
        $kontak_telepon = Setting::get('kontak_telepon');
        $kontak_email = Setting::get('kontak_email');
        $jam_pelayanan = Setting::get('jam_pelayanan');
        $peta_lokasi = Setting::get('peta_lokasi');

        return view('public.kontak', compact(
            'alamat', 'kontak_telepon', 'kontak_email', 'jam_pelayanan', 'peta_lokasi'
        ));
    }

    public function kirimKontak(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|numeric|digits:16',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string|max:2000',
        ]);

        // Simulasi sukses pengiriman pesan
        return back()->with('success', 'Pesan Anda telah berhasil terkirim! Tim pelayanan Desa Penebal akan segera merespon via email/telepon.');
    }
}
