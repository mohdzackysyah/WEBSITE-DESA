<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pengajuan',
        'nik',
        'nama_lengkap',
        'jenis_surat',
        'form_data',
        'status',
        'catatan_operator',
        'alasan_penolakan',
        'berkas_pendukung',
        'dokumen_final',
        'nomor_surat',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'nik', 'nik');
    }
}
