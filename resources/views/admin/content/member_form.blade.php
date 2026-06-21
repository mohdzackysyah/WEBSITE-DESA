@extends('layouts.admin')

@section('title', isset($member) ? 'Edit Anggota Organisasi' : 'Tambah Anggota Organisasi')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>{{ isset($member) ? 'Ubah Data Anggota' : 'Tambah Anggota Baru' }}</h1>
            <p>{{ isset($member) ? 'Perbarui informasi anggota struktur organisasi desa.' : 'Daftarkan aparatur desa baru ke bagan organisasi kepengurusan.' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.content.members.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
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
            action="{{ isset($member) ? route('admin.content.members.update', $member->id) : route('admin.content.members.store') }}" 
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if(isset($member))
                @method('PUT')
            @endif

            <h3 style="font-size: 16px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px;">Data Aparatur Desa</h3>

            <div class="grid-2">
                <div class="form-group">
                    <label for="name">Nama Lengkap <span style="color:red;">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $member->name ?? '') }}" 
                        class="form-control" 
                        placeholder="Nama Lengkap beserta gelar"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="position">Jabatan / Posisi <span style="color:red;">*</span></label>
                    <input 
                        type="text" 
                        name="position" 
                        id="position" 
                        value="{{ old('position', $member->position ?? '') }}" 
                        class="form-control" 
                        placeholder="Contoh: Kaur Keuangan, Kepala Seksi Pelayanan"
                        required
                    >
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="level">Tingkatan Struktur <span style="color:red;">*</span></label>
                    <select name="level" id="level" class="form-control" required>
                        <option value="">-- Pilih Tingkatan --</option>
                        <option value="1" {{ old('level', $member->level ?? '') == 1 ? 'selected' : '' }}>Kepala Desa (Level 1)</option>
                        <option value="2" {{ old('level', $member->level ?? '') == 2 ? 'selected' : '' }}>Sekretaris Desa (Level 2)</option>
                        <option value="3" {{ old('level', $member->level ?? '') == 3 ? 'selected' : '' }}>Kasi & Kaur / Perangkat Desa (Level 3)</option>
                        <option value="4" {{ old('level', $member->level ?? '') == 4 ? 'selected' : '' }}>Kepala Dusun (Level 4)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sort_order">Urutan Tampil (Sort Order) <span style="color:red;">*</span></label>
                    <input 
                        type="number" 
                        name="sort_order" 
                        id="sort_order" 
                        value="{{ old('sort_order', $member->sort_order ?? '1') }}" 
                        class="form-control" 
                        min="0"
                        required
                    >
                    <small style="color: var(--text-light); margin-top: 4px; display: block;">Menentukan urutan tampilan bagan secara berurutan kecil ke besar.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Tugas / Fungsi Kerja</label>
                <textarea 
                    name="description" 
                    id="description" 
                    class="form-control" 
                    placeholder="Tuliskan uraian tugas singkat atau pembinaan wilayah kerja..."
                >{{ old('description', $member->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="photo">Foto Profil Aparatur</label>
                @if(isset($member) && $member->photo)
                    <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 16px;">
                        <img src="{{ asset($member->photo) }}" alt="Foto Lama" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
                        <div>
                            <span style="font-size: 13px; color: var(--text-secondary); display: block;">Foto saat ini terunggah.</span>
                            <span style="font-size: 11px; color: var(--text-light);">Unggah file baru untuk menggantinya.</span>
                        </div>
                    </div>
                @endif
                <input 
                    type="file" 
                    name="photo" 
                    id="photo" 
                    class="form-control" 
                    accept="image/*"
                >
                <small style="color: var(--text-light); margin-top: 4px; display: block;">Format yang didukung: JPG, JPEG, PNG, WEBP. Ukuran berkas tidak dibatasi, sistem otomatis mengoptimasi gambar.</small>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    @if(isset($member))
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Simpan Perubahan
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Daftarkan Anggota
                    @endif
                </button>
                <a href="{{ route('admin.content.members.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
