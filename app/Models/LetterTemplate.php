<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $fillable = [
        'jenis_surat',
        'nama_surat',
        'file_path',
    ];
}
