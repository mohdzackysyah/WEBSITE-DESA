<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SuratRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationMemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// PORTAL WARGA (PUBLIC ROUTES)
// ==========================================
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/profil', [PageController::class, 'profil'])->name('profil');
Route::get('/profil/tentang', [PageController::class, 'profil'])->name('profil.tentang');
Route::get('/profil/struktur', [PageController::class, 'struktur'])->name('profil.struktur');
Route::get('/berita', [PageController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [PageController::class, 'detailBerita'])->name('berita.detail');
Route::get('/galeri', [PageController::class, 'galeri'])->name('galeri');
Route::get('/statistik', [PageController::class, 'statistik'])->name('statistik');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [PageController::class, 'kirimKontak'])->name('kontak.store');

// Layanan Surat & Pelacakan
Route::get('/layanan', [SuratRequestController::class, 'showLayanan'])->name('layanan.index');
Route::get('/layanan/lacak', [SuratRequestController::class, 'showLacak'])->name('layanan.lacak');
Route::get('/layanan/check-nik', [SuratRequestController::class, 'checkNik'])->name('layanan.check-nik');
Route::get('/layanan/form/{type}', [SuratRequestController::class, 'showForm'])->name('layanan.form');
Route::post('/layanan/form/{type}', [SuratRequestController::class, 'store'])->name('layanan.form.store');
Route::get('/layanan/download/{id}', [SuratRequestController::class, 'downloadFinal'])->name('layanan.download');
Route::get('/layanan/preview/{id}', [SuratRequestController::class, 'previewFinal'])->name('layanan.preview');

// ==========================================
// AUTENTIKASI OPERATOR
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// PANEL OPERATOR (ADMIN ROUTES - AUTH REQUIRED)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Operator Pelayanan (Daftar Pengajuan & Proses Surat)
    Route::get('/surat', [SuratRequestController::class, 'adminDashboard'])->name('surat.index');
    Route::get('/surat/detail/{id}', [SuratRequestController::class, 'showDetail'])->name('surat.detail');
    Route::get('/surat/attachment/{id}', [SuratRequestController::class, 'downloadAttachment'])->name('surat.attachment');
    Route::post('/surat/status/{id}', [SuratRequestController::class, 'updateStatus'])->name('surat.status');
    Route::get('/surat/draft/{id}', [SuratRequestController::class, 'generateDraft'])->name('surat.draft');
    Route::get('/surat/preview-draft/{id}', [SuratRequestController::class, 'previewDraft'])->name('surat.preview-draft');
    Route::get('/surat/preview-final/{id}', [SuratRequestController::class, 'previewFinal'])->name('surat.preview-final');
    Route::get('/surat/templates', [\App\Http\Controllers\LetterTemplateController::class, 'index'])->name('surat.templates.index');
    Route::post('/surat/templates/{jenis}', [\App\Http\Controllers\LetterTemplateController::class, 'store'])->name('surat.templates.store');
    Route::get('/surat/templates/download/{jenis}', [\App\Http\Controllers\LetterTemplateController::class, 'download'])->name('surat.templates.download');
    Route::get('/surat/templates/preview/{jenis}', [\App\Http\Controllers\LetterTemplateController::class, 'preview'])->name('surat.templates.preview');

    // Operator Konten (Berita, Galeri, Profil Setting)
    Route::get('/content/posts', [ContentController::class, 'postsIndex'])->name('content.posts');
    Route::get('/content/posts/create', [ContentController::class, 'postCreate'])->name('content.posts.create');
    Route::post('/content/posts', [ContentController::class, 'postStore'])->name('content.posts.store');
    Route::get('/content/posts/{id}/edit', [ContentController::class, 'postEdit'])->name('content.posts.edit');
    Route::put('/content/posts/{id}', [ContentController::class, 'postUpdate'])->name('content.posts.update');
    Route::delete('/content/posts/{id}', [ContentController::class, 'postDestroy'])->name('content.posts.destroy');

    Route::get('/content/galleries', [ContentController::class, 'galleriesIndex'])->name('content.galleries');
    Route::post('/content/galleries', [ContentController::class, 'galleryStore'])->name('content.galleries.store');
    Route::delete('/content/galleries/{id}', [ContentController::class, 'galleryDestroy'])->name('content.galleries.destroy');

    Route::get('/content/settings', [ContentController::class, 'settingsIndex'])->name('content.settings');
    Route::post('/content/settings', [ContentController::class, 'settingsUpdate'])->name('content.settings.update');

    // Operator Konten - Struktur Organisasi
    Route::get('/content/members', [OrganizationMemberController::class, 'index'])->name('content.members.index');
    Route::get('/content/members/create', [OrganizationMemberController::class, 'create'])->name('content.members.create');
    Route::post('/content/members', [OrganizationMemberController::class, 'store'])->name('content.members.store');
    Route::get('/content/members/{id}/edit', [OrganizationMemberController::class, 'edit'])->name('content.members.edit');
    Route::put('/content/members/{id}', [OrganizationMemberController::class, 'update'])->name('content.members.update');
    Route::delete('/content/members/{id}', [OrganizationMemberController::class, 'destroy'])->name('content.members.destroy');

    // Operator Konten / Admin (Kelola Data Penduduk & Statistik)
    Route::get('/content/residents', [ResidentController::class, 'index'])->name('content.residents');
    Route::get('/content/residents/create', [ResidentController::class, 'create'])->name('content.residents.create');
    Route::post('/content/residents', [ResidentController::class, 'store'])->name('content.residents.store');
    Route::get('/content/residents/{nik}/edit', [ResidentController::class, 'edit'])->name('content.residents.edit');
    Route::put('/content/residents/{nik}', [ResidentController::class, 'update'])->name('content.residents.update');
    Route::delete('/content/residents/{nik}', [ResidentController::class, 'destroy'])->name('content.residents.destroy');
});
