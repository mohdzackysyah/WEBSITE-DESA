<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::orderBy('nama_lengkap');

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('nik', 'like', $term)
                  ->orWhere('nama_lengkap', 'like', $term)
                  ->orWhere('alamat', 'like', $term);
            });
        }

        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        $residents = $query->paginate(15);

        // Summary stats
        $stats = [
            'total' => Resident::count(),
            'laki' => Resident::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Resident::where('jenis_kelamin', 'P')->count(),
            'umkm' => Resident::where('is_umkm', true)->count(),
        ];

        $rwList = Resident::select('rw')->distinct()->orderBy('rw')->pluck('rw');

        return view('admin.content.residents', compact('residents', 'stats', 'rwList'));
    }

    public function create()
    {
        return view('admin.content.resident_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:residents,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:100',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'pendidikan' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'bantuan_sosial' => 'required|string|max:100',
            'is_umkm' => 'nullable|boolean',
            'nama_umkm' => 'nullable|string|max:255',
        ]);

        Resident::create([
            ...$request->only([
                'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
                'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'rt', 'rw',
                'pendidikan', 'status_perkawinan', 'bantuan_sosial', 'nama_umkm',
            ]),
            'is_umkm' => $request->boolean('is_umkm'),
        ]);

        return redirect()->route('admin.content.residents')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function edit($nik)
    {
        $resident = Resident::where('nik', $nik)->firstOrFail();
        return view('admin.content.resident_form', compact('resident'));
    }

    public function update(Request $request, $nik)
    {
        $resident = Resident::where('nik', $nik)->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:100',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'pendidikan' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'bantuan_sosial' => 'required|string|max:100',
            'is_umkm' => 'nullable|boolean',
            'nama_umkm' => 'nullable|string|max:255',
        ]);

        $resident->update([
            ...$request->only([
                'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
                'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'rt', 'rw',
                'pendidikan', 'status_perkawinan', 'bantuan_sosial', 'nama_umkm',
            ]),
            'is_umkm' => $request->boolean('is_umkm'),
        ]);

        return redirect()->route('admin.content.residents')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy($nik)
    {
        $resident = Resident::where('nik', $nik)->firstOrFail();
        $resident->delete();

        return redirect()->route('admin.content.residents')->with('success', 'Data penduduk berhasil dihapus.');
    }
}
