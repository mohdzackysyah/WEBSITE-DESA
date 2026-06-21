@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Berita' : 'Tambah Berita Baru')

@section('content')
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.content.posts') }}" style="color: var(--text-light); display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke Daftar Berita
        </a>
    </div>

    <div class="page-header" style="margin-bottom: 32px;">
        <div class="page-title">
            <h1>{{ isset($post) ? 'Edit Berita' : 'Tambah Berita Baru' }}</h1>
            <p>Tulis artikel atau pengumuman kemasyarakatan terbaru desa.</p>
        </div>
    </div>

    <div class="form-card">
        <form 
            action="{{ isset($post) ? route('admin.content.posts.update', $post->id) : route('admin.content.posts.store') }}" 
            method="POST" 
            enctype="multipart/form-data"
        >
            @csrf
            @if(isset($post))
                @method('PUT')
            @endif

            <!-- Title -->
            <div class="form-group">
                <label for="title">Judul Berita</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $post->title ?? '') }}" 
                    class="form-control" 
                    placeholder="Contoh: Panen Raya Sawah Krajan Berjalan Lancar"
                    required
                >
            </div>

            <!-- Content -->
            <div class="form-group">
                <label for="content">Isi Konten Berita</label>
                <textarea 
                    name="content" 
                    id="content" 
                    class="form-control" 
                    style="min-height: 260px;" 
                    placeholder="Tulis detail berita di sini..."
                    required
                >{{ old('content', $post->content ?? '') }}</textarea>
            </div>

            <!-- Grid: Image & Status -->
            <div class="grid-2">
                <!-- Image upload -->
                <div class="form-group">
                    <label for="image">Gambar Ilustrasi Berita</label>
                    <input type="file" name="image" id="image" accept="image/*" class="form-control">
                    <span style="font-size: 11px; color: var(--text-light); margin-top: 4px; display: block;">Format gambar: jpg, jpeg, png (Ukuran berkas tidak dibatasi, sistem otomatis mengoptimasi gambar)</span>
                    
                    @if(isset($post) && $post->image)
                        <div style="margin-top: 12px;">
                            <span style="font-size: 12px; font-weight: bold; display: block; margin-bottom: 6px;">Gambar Saat Ini:</span>
                            <img src="{{ $post->image }}" alt="Ilustrasi" style="width: 160px; height: 100px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        </div>
                    @endif
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">Status Penerbitan</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="draft" {{ old('status', $post->status ?? '') === 'draft' ? 'selected' : '' }}>Simpan Sebagai Draft</option>
                        <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Publikasikan Langsung (Published)</option>
                    </select>
                    <span style="font-size: 11px; color: var(--text-light); margin-top: 4px; display: block;">Pilih 'Published' agar dapat langsung dibaca oleh warga di halaman berita.</span>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; display: inline-flex; align-items: center; gap: 8px;">
                    @if(isset($post))
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Simpan Perubahan Berita
                    @else
                        Terbitkan Berita Baru
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    @endif
                </button>
                <a href="{{ route('admin.content.posts') }}" class="btn btn-secondary" style="padding: 12px 24px; display: inline-flex; align-items: center;">Batal</a>
            </div>
        </form>
    </div>
@endsection
