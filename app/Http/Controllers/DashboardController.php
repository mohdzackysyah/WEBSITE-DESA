<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Gallery;
use App\Models\Resident;
use App\Models\Setting;
use App\Models\SuratRequest;
use App\Models\UploadLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        $data = ['role' => $role, 'user' => $user];

        // ==========================================
        // OPERATOR PELAYANAN
        // ==========================================
        if ($role === 'operator_pelayanan' || $role === 'admin') {
            $data['surat_total'] = SuratRequest::count();
            $data['surat_menunggu'] = SuratRequest::where('status', 'menunggu_verifikasi')->count();
            $data['surat_diproses'] = SuratRequest::where('status', 'diproses')->count();
            $data['surat_selesai'] = SuratRequest::where('status', 'selesai')->count();
            $data['surat_ditolak'] = SuratRequest::where('status', 'ditolak')->count();
            $data['surat_terbaru'] = SuratRequest::orderBy('created_at', 'desc')->take(5)->get();
            $data['surat_bulan_ini'] = SuratRequest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        // ==========================================
        // OPERATOR KONTEN
        // ==========================================
        if ($role === 'operator_konten' || $role === 'admin') {
            $data['berita_total'] = Post::count();
            $data['berita_published'] = Post::where('status', 'published')->count();
            $data['berita_draft'] = Post::where('status', 'draft')->count();
            $data['galeri_total'] = Gallery::count();
            $data['penduduk_total'] = Resident::count();
            $data['penduduk_laki'] = Resident::where('jenis_kelamin', 'L')->count();
            $data['penduduk_perempuan'] = Resident::where('jenis_kelamin', 'P')->count();
            $data['umkm_total'] = Resident::where('is_umkm', true)->count();
            $data['berita_terbaru'] = Post::orderBy('created_at', 'desc')->take(5)->get();
        }

        // ==========================================
        // ADMIN (SUPER ADMIN) - EXTRA DATA
        // ==========================================
        if ($role === 'admin') {
            $data['upload_logs'] = UploadLog::orderBy('uploaded_at', 'desc')->take(5)->get();
            $data['upload_total'] = UploadLog::count();
            $data['upload_invalid'] = UploadLog::where('is_valid', false)->count();
            $data['nama_desa'] = Setting::get('nama_desa', 'Desa Penebal');
        }

        return view('admin.dashboard', $data);
    }
}
