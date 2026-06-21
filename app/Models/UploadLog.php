<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'filename',
        'mime_type',
        'file_size',
        'ip_address',
        'is_valid',
        'validation_errors',
        'uploaded_at',
    ];

    protected $casts = [
        'validation_errors' => 'array',
        'is_valid' => 'boolean',
        'uploaded_at' => 'datetime',
    ];
}
