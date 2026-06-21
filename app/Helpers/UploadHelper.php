<?php

namespace App\Helpers;

use App\Models\UploadLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadHelper
{
    /**
     * Process an uploaded file securely with validation, compression, and EXIF stripping.
     */
    public static function processUpload(UploadedFile $file, $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'], $isPublic = false, $subDir = null)
    {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $ipAddress = request()->ip();
        $extension = strtolower($file->getClientOriginalExtension());
        
        $isValid = true;
        $errors = [];

        // 1. Deteksi ekstensi ganda
        if (substr_count($originalName, '.') > 1) {
            $isValid = false;
            $errors[] = 'Deteksi ekstensi ganda yang mencurigakan.';
        }

        // 2. Validasi ekstensi
        if (!in_array($extension, $allowedExtensions)) {
            $isValid = false;
            $errors[] = 'Ekstensi file tidak diizinkan. Hanya menerima: ' . implode(', ', $allowedExtensions);
        }

        // 3. Validasi tipe MIME
        $expectedMimes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp'
        ];

        if (isset($expectedMimes[$extension]) && $expectedMimes[$extension] !== $mimeType) {
            $isValid = false;
            $errors[] = 'Tipe MIME berkas tidak sesuai dengan ekstensi file.';
        }

        // Simpan log upload ke database
        $log = UploadLog::create([
            'filename' => $originalName,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'ip_address' => $ipAddress,
            'is_valid' => $isValid,
            'validation_errors' => $errors,
            'uploaded_at' => now(),
        ]);

        if (!$isValid) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        // 4. Proteksi Server: Nama berkas acak & Direktori terisolasi
        $randomName = Str::random(40) . '.' . $extension;
        $tempPath = $file->getRealPath();
        
        $relativeDir = $subDir ? 'uploads/' . trim($subDir, '/') : 'uploads/' . date('Y/m');
        
        if ($isPublic) {
            $fullUploadPath = public_path($relativeDir);
        } else {
            $fullUploadPath = storage_path('app/private/' . $relativeDir);
        }

        if (!file_exists($fullUploadPath)) {
            mkdir($fullUploadPath, 0755, true);
        }

        $destinationPath = $fullUploadPath . '/' . $randomName;

        // 5. Kompresi Gambar & Penghapusan Metadata EXIF secara otomatis lewat library GD PHP
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            try {
                $imgData = file_get_contents($tempPath);
                $src = @imagecreatefromstring($imgData);
                if (!$src) {
                    $log->update([
                        'is_valid' => false,
                        'validation_errors' => array_merge($log->validation_errors ?? [], ['Struktur gambar rusak atau tidak valid.'])
                    ]);
                    return [
                        'success' => false,
                        'errors' => ['File gambar rusak atau tidak valid.']
                    ];
                }

                $width = imagesx($src);
                $height = imagesy($src);
                $maxDim = 1200;

                // Resize otomatis jika terlalu besar (max 1200px)
                if ($width > $maxDim || $height > $maxDim) {
                    $ratio = $width / $height;
                    if ($ratio > 1) {
                        $newWidth = $maxDim;
                        $newHeight = round($maxDim / $ratio);
                    } else {
                        $newHeight = $maxDim;
                        $newWidth = round($maxDim * $ratio);
                    }
                    $dst = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Pertahankan transparansi PNG & WEBP
                    if (in_array($extension, ['png', 'webp'])) {
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                        imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
                    }

                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($src);
                    $src = $dst;
                }

                // Simpan & kompresi (GD otomatis menghapus EXIF)
                if ($extension === 'png') {
                    imagepng($src, $destinationPath, 6); // Kompresi level 6 (0-9)
                } elseif ($extension === 'webp') {
                    imagewebp($src, $destinationPath, 80); // Kualitas 80%
                } else {
                    imagejpeg($src, $destinationPath, 80); // Kualitas 80%
                }
                imagedestroy($src);

            } catch (\Exception $e) {
                // Fallback jika GD bermasalah
                move_uploaded_file($tempPath, $destinationPath);
            }
        } else {
            // PDF atau file lainnya disimpan langsung
            move_uploaded_file($tempPath, $destinationPath);
        }

        return [
            'success' => true,
            'path' => ($isPublic ? '/' : '') . $relativeDir . '/' . $randomName,
            'filename' => $originalName
        ];
    }
}
