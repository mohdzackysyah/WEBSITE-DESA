@extends('layouts.admin')

@section('title', 'Kelola Galeri Foto')

@section('content')
    <div class="page-header" style="margin-bottom: 32px;">
        <div class="page-title">
            <h1>Kelola Galeri Foto Desa</h1>
            <p>Unggah dokumentasi foto kegiatan masyarakat, sarana infrastruktur, dan sarana umum desa.</p>
        </div>
    </div>

    <div class="grid-2" style="grid-template-columns: 1fr 2.2fr; align-items: start;">
        <!-- LEFT: FORM UPLOAD NEW PHOTO -->
        <div class="form-card" style="padding: 24px;">
            <h3 style="font-size: 16px; margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Unggah Foto Baru</h3>
            
            <form action="{{ route('admin.content.galleries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Judul / Keterangan Foto</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}" 
                        class="form-control" 
                        placeholder="Contoh: Pembagian Sembako Dusun Wana" 
                        required
                    >
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category">Kategori Dokumentasi</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="kegiatan">Kegiatan Kemasyarakatan</option>
                        <option value="pembangunan">Pembangunan Desa</option>
                        <option value="fasilitas">Fasilitas & Sarana</option>
                    </select>
                </div>

                <!-- Image File -->
                <div class="form-group">
                    <label for="image">Berkas Foto</label>
                    <input type="file" name="image" id="image" accept="image/*" class="form-control" required>
                    <span style="font-size: 11px; color: var(--text-light); margin-top: 4px; display: block;">Format gambar: jpg, jpeg, png (Ukuran berkas tidak dibatasi, sistem otomatis mengoptimasi gambar)</span>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                    Simpan & Unggah Foto
                </button>
            </form>
        </div>

        <!-- RIGHT: GALLERY GRID -->
        <div>
            <div class="table-card" style="padding: 24px;">
                <h3 style="font-size: 16px; margin-bottom: 20px; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Koleksi Galeri Terkini</h3>
                
                <div class="admin-gallery-grid">
                    @forelse($galleries as $g)
                        <div class="admin-gallery-card">
                            <img src="{{ $g->image }}" alt="Gallery" class="admin-gallery-img">
                            <div class="admin-gallery-body">
                                <h4 class="admin-gallery-title" title="{{ $g->title }}">{{ $g->title }}</h4>
                                <span class="admin-gallery-cat">{{ strtoupper($g->category) }}</span>
                                
                                <form action="{{ route('admin.content.galleries.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini dari galeri?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; padding: 6px 0; font-size: 11px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        Hapus Foto
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: span 4; text-align: center; color: var(--text-light); padding: 40px;">
                            Belum ada foto yang diunggah.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($galleries->total() > $galleries->perPage())
                    <div style="margin-top: 24px; display: flex; justify-content: center;">
                        {{ $galleries->links('vendor.pagination.simple-default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
