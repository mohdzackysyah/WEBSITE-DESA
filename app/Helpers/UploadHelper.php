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
    public static function processUpload(UploadedFile $file, $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'], $isPublic = false, $subDir = null, $optimize = true)
    {
        if (!$file->isValid()) {
            return [
                'success' => false,
                'errors' => ['Gagal mengunggah berkas: ' . $file->getErrorMessage()]
            ];
        }

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
            'pdf' => ['application/pdf'],
            'jpg' => ['image/jpeg', 'image/pjpeg'],
            'jpeg' => ['image/jpeg', 'image/pjpeg'],
            'png' => ['image/png'],
            'webp' => ['image/webp'],
            'doc' => ['application/msword', 'application/vnd.ms-office', 'application/octet-stream'],
            'docx' => [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/zip',
                'application/octet-stream'
            ]
        ];

        if (isset($expectedMimes[$extension])) {
            if (!in_array($mimeType, $expectedMimes[$extension])) {
                $isValid = false;
                $errors[] = 'Tipe MIME berkas tidak sesuai dengan ekstensi file.';
            }
        }

        // 4. Validasi Struktur Internal Berkas (Magic Bytes / Header)
        if ($isValid) {
            $tempPath = $file->getRealPath();
            if ($extension === 'pdf') {
                $handle = @fopen($tempPath, 'r');
                if ($handle) {
                    $firstBytes = fread($handle, 5);
                    fclose($handle);
                    if ($firstBytes !== '%PDF-') {
                        $isValid = false;
                        $errors[] = 'Struktur berkas PDF tidak valid (header rusak).';
                    }
                } else {
                    $isValid = false;
                    $errors[] = 'Gagal membaca isi berkas PDF.';
                }
            } elseif ($extension === 'docx') {
                if (class_exists('ZipArchive')) {
                    $zip = new \ZipArchive();
                    if ($zip->open($tempPath) !== true) {
                        $isValid = false;
                        $errors[] = 'Berkas Word (DOCX) rusak atau tidak valid.';
                    } else {
                        $zip->close();
                    }
                }
            } elseif ($extension === 'doc') {
                $handle = @fopen($tempPath, 'r');
                if ($handle) {
                    $firstBytes = fread($handle, 8);
                    fclose($handle);
                    $oleSignature = "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1";
                    if ($firstBytes !== $oleSignature) {
                        $isValid = false;
                        $errors[] = 'Berkas Word (DOC) rusak atau tidak valid.';
                    }
                }
            }
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

        // 5. Kompresi & Optimasi (Hanya dilakukan jika $optimize bernilai true)
        if ($optimize && in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
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
                $file->move($fullUploadPath, $randomName);
            }
        } else {
            // Pindahkan file asli terlebih dahulu
            $file->move($fullUploadPath, $randomName);

            // Jika $optimize bernilai true, coba optimalkan format berkas Word atau PDF
            if ($optimize) {
                if ($extension === 'docx') {
                    self::optimizeDocx($destinationPath);
                } elseif ($extension === 'pdf') {
                    self::optimizePdf($destinationPath);
                }
            }
        }

        return [
            'success' => true,
            'path' => ($isPublic ? '/' : '') . $relativeDir . '/' . $randomName,
            'filename' => $originalName
        ];
    }

    /**
     * Compress images inside a Word DOCX file using GD and ZipArchive.
     */
    private static function optimizeDocx($filePath)
    {
        if (!class_exists('ZipArchive')) {
            return;
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            $imagesToOptimize = [];
            
            // Loop through zip entries to find images inside word/media/
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'];
                
                if (strpos($name, 'word/media/') === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        $imagesToOptimize[] = [
                            'index' => $i,
                            'name' => $name,
                            'ext' => $ext
                        ];
                    }
                }
            }

            if (!empty($imagesToOptimize)) {
                foreach ($imagesToOptimize as $img) {
                    $originalData = $zip->getFromIndex($img['index']);
                    if (!$originalData) continue;

                    $src = @imagecreatefromstring($originalData);
                    if (!$src) continue;

                    $width = imagesx($src);
                    $height = imagesy($src);
                    $maxDim = 1000;

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
                        
                        if ($img['ext'] === 'png') {
                            imagealphablending($dst, false);
                            imagesavealpha($dst, true);
                            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                            imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
                        }

                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagedestroy($src);
                        $src = $dst;
                    }

                    // Compress image directly to memory
                    ob_start();
                    if ($img['ext'] === 'png') {
                        imagepng($src, null, 6);
                    } else {
                        imagejpeg($src, null, 75);
                    }
                    $compressedData = ob_get_clean();
                    imagedestroy($src);

                    if ($compressedData) {
                        $zip->addFromString($img['name'], $compressedData);
                    }
                }
            }

            $zip->close();
        }
    }

    /**
     * Compress PDF files using Ghostscript if available.
     */
    private static function optimizePdf($filePath)
    {
        $tempPdf = $filePath . '.temp.pdf';
        $gsCommands = ['gs', 'gswin64c', 'gswin32c'];
        
        foreach ($gsCommands as $gs) {
            $cmd = "{$gs} -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . escapeshellarg($tempPdf) . " " . escapeshellarg($filePath);
            
            $output = [];
            $returnCode = -1;
            @exec($cmd, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($tempPdf) && filesize($tempPdf) > 0) {
                if (filesize($tempPdf) < filesize($filePath)) {
                    @unlink($filePath);
                    @rename($tempPdf, $filePath);
                } else {
                    @unlink($tempPdf);
                }
                break;
            } else {
                if (file_exists($tempPdf)) {
                    @unlink($tempPdf);
                }
            }
        }
    }
}
