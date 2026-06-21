<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan')->unique();
            $table->string('nik');
            $table->string('nama_lengkap');
            $table->string('jenis_surat'); // 'domisili', 'sktm', 'pindah'
            $table->json('form_data'); // custom fields specific to the letter
            $table->string('status')->default('menunggu_verifikasi'); // 'menunggu_verifikasi', 'diproses', 'selesai', 'ditolak'
            $table->text('catatan_operator')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->string('berkas_pendukung')->nullable(); // path to KTP/KK scan
            $table->string('dokumen_final')->nullable(); // path to final signed PDF
            $table->string('nomor_surat')->nullable(); // official letter number (e.g. 470/015/DSA/VI/2026)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_requests');
    }
};
