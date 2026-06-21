<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ContentController extends Controller
{
    // ==========================================
    // MANAJEMEN BERITA (POSTS)
    // ==========================================

    public function postsIndex()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.content.posts', compact('posts'));
    }

    public function postCreate()
    {
        return view('admin.content.post_form');
    }

    public function postStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'status' => 'required|in:draft,published',
        ]);

        $post = new Post($request->only(['title', 'content', 'status']));

        if ($request->hasFile('image')) {
            $uploadResult = \App\Helpers\UploadHelper::processUpload($request->file('image'), ['jpeg', 'png', 'jpg'], true, 'posts');
            if (!$uploadResult['success']) {
                return back()->withErrors(['image' => implode(' ', $uploadResult['errors'])])->withInput();
            }
            $post->image = $uploadResult['path'];
        }

        $post->save();

        return redirect()->route('admin.content.posts')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function postEdit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.content.post_form', compact('post'));
    }

    public function postUpdate(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'status' => 'required|in:draft,published',
        ]);

        $post->fill($request->only(['title', 'content', 'status']));
        $post->slug = Str::slug($request->title); // update slug on title change

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            $oldPath = public_path(ltrim($post->image, '/'));
            if ($post->image && file_exists($oldPath)) {
                @unlink($oldPath);
            }

            $uploadResult = \App\Helpers\UploadHelper::processUpload($request->file('image'), ['jpeg', 'png', 'jpg'], true, 'posts');
            if (!$uploadResult['success']) {
                return back()->withErrors(['image' => implode(' ', $uploadResult['errors'])])->withInput();
            }
            $post->image = $uploadResult['path'];
        }

        $post->save();

        return redirect()->route('admin.content.posts')->with('success', 'Berita berhasil diperbarui.');
    }

    public function postDestroy($id)
    {
        $post = Post::findOrFail($id);
        
        $oldPath = public_path(ltrim($post->image, '/'));
        if ($post->image && file_exists($oldPath)) {
            @unlink($oldPath);
        }

        $post->delete();
        return redirect()->route('admin.content.posts')->with('success', 'Berita berhasil dihapus.');
    }

    // ==========================================
    // MANAJEMEN GALERI (GALLERIES)
    // ==========================================

    public function galleriesIndex()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.content.galleries', compact('galleries'));
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:kegiatan,pembangunan,fasilitas',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $gallery = new Gallery($request->only(['title', 'category']));

        if ($request->hasFile('image')) {
            $uploadResult = \App\Helpers\UploadHelper::processUpload($request->file('image'), ['jpeg', 'png', 'jpg'], true, 'gallery');
            if (!$uploadResult['success']) {
                return back()->withErrors(['image' => implode(' ', $uploadResult['errors'])])->withInput();
            }
            $gallery->image = $uploadResult['path'];
        }

        $gallery->save();

        return redirect()->route('admin.content.galleries')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function galleryDestroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        $oldPath = public_path(ltrim($gallery->image, '/'));
        if ($gallery->image && file_exists($oldPath)) {
            @unlink($oldPath);
        }

        $gallery->delete();
        return redirect()->route('admin.content.galleries')->with('success', 'Foto galeri berhasil dihapus.');
    }

    // ==========================================
    // PENGATURAN PROFIL DESA (SETTINGS)
    // ==========================================

    public function settingsIndex()
    {
        $settings = [
            'sejarah' => Setting::get('sejarah'),
            'sambutan' => Setting::get('sambutan'),
            'nama_kepala' => Setting::get('nama_kepala'),
            'visi' => Setting::get('visi'),
            'misi' => Setting::get('misi'),
            'alamat' => Setting::get('alamat'),
            'kontak_telepon' => Setting::get('kontak_telepon'),
            'kontak_email' => Setting::get('kontak_email'),
            'jam_pelayanan' => Setting::get('jam_pelayanan'),
            'peta_lokasi' => Setting::get('peta_lokasi'),
        ];

        return view('admin.content.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'sejarah' => 'required|string',
            'sambutan' => 'required|string',
            'nama_kepala' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'alamat' => 'required|string',
            'kontak_telepon' => 'required|string|max:255',
            'kontak_email' => 'required|email|max:255',
            'jam_pelayanan' => 'required|string',
            'peta_lokasi' => 'required|string',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.content.settings')->with('success', 'Profil dan pengaturan desa berhasil diperbarui.');
    }
}
