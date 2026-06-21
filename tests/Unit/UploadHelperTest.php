<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\UploadHelper;
use App\Models\UploadLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadHelperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set up local storage disks if needed
        Storage::fake('local');
    }

    /**
     * Test image optimization (resize & EXIF stripping via GD)
     */
    public function test_image_optimization_resizes_large_image()
    {
        // Create a fake large image (1500x1000)
        $file = UploadedFile::fake()->image('photo.jpg', 1500, 1000);

        // Upload and optimize
        $result = UploadHelper::processUpload($file, ['jpg', 'jpeg', 'png'], false, 'test-images', true);

        $this->assertTrue($result['success']);
        $uploadedPath = storage_path('app/private/' . $result['path']);
        $this->assertFileExists($uploadedPath);

        // Verify dimensions (should be resized to max 1200 width)
        list($width, $height) = getimagesize($uploadedPath);
        $this->assertEquals(1200, $width);
        $this->assertEquals(800, $height); // 1200 * (1000 / 1500) = 800

        // Clean up
        @unlink($uploadedPath);
    }

    /**
     * Test that image upload with $optimize = false preserves original size and content
     */
    public function test_image_upload_without_optimization_preserves_original()
    {
        // Create a fake large image
        $file = UploadedFile::fake()->image('photo.jpg', 1500, 1000);
        $originalSize = $file->getSize();

        // Upload without optimization
        $result = UploadHelper::processUpload($file, ['jpg', 'jpeg', 'png'], false, 'test-images', false);

        $this->assertTrue($result['success']);
        $uploadedPath = storage_path('app/private/' . $result['path']);
        $this->assertFileExists($uploadedPath);

        // Verify dimensions are still 1500x1000
        list($width, $height) = getimagesize($uploadedPath);
        $this->assertEquals(1500, $width);
        $this->assertEquals(1000, $height);

        // Clean up
        @unlink($uploadedPath);
    }

    /**
     * Test PDF upload optimization (runs Ghostscript or falls back)
     */
    public function test_pdf_upload_with_optimization_runs_successfully()
    {
        // Create a mock PDF content (basic PDF structure or simple text)
        $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] >>\nendobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n180\n%%EOF";
        
        $tempPdf = tempnam(sys_get_temp_dir(), 'test_pdf');
        file_put_contents($tempPdf, $pdfContent);
        
        $file = new UploadedFile($tempPdf, 'document.pdf', 'application/pdf', null, true);

        // Upload with optimization
        $result = UploadHelper::processUpload($file, ['pdf'], false, 'test-pdf', true);

        $this->assertTrue($result['success']);
        $uploadedPath = storage_path('app/private/' . $result['path']);
        $this->assertFileExists($uploadedPath);

        // Clean up
        @unlink($uploadedPath);
    }

    /**
     * Test PDF upload without optimization (should preserve exact original file for admin)
     */
    public function test_pdf_upload_without_optimization_preserves_original_hash()
    {
        // Create a PDF with specific content
        $pdfContent = "%PDF-1.4 - UNIQUE ADMIN STAMP ATTESTATION - " . uniqid();
        $tempPdf = tempnam(sys_get_temp_dir(), 'test_admin_pdf');
        file_put_contents($tempPdf, $pdfContent);
        $originalHash = md5($pdfContent);

        $file = new UploadedFile($tempPdf, 'document_final.pdf', 'application/pdf', null, true);

        // Upload with $optimize = false (Admin signature preservation)
        $result = UploadHelper::processUpload($file, ['pdf'], false, 'test-admin-pdf', false);

        $this->assertTrue($result['success']);
        $uploadedPath = storage_path('app/private/' . $result['path']);
        $this->assertFileExists($uploadedPath);

        // Verify the saved file is identical to the source file
        $uploadedHash = md5_file($uploadedPath);
        $this->assertEquals($originalHash, $uploadedHash, "Admin uploaded PDF must match the original file hash exactly.");

        // Clean up
        @unlink($uploadedPath);
    }

    /**
     * Test Docx optimization (extracts and compresses embedded images)
     */
    public function test_docx_upload_optimization_compresses_embedded_images()
    {
        if (!class_exists('ZipArchive')) {
            $this->markTestSkipped('ZipArchive extension is not loaded.');
        }

        // 1. Create a dummy docx file (which is a ZIP file) containing a large image under word/media/
        $tempDocxPath = tempnam(sys_get_temp_dir(), 'test_docx') . '.docx';
        
        $zip = new \ZipArchive();
        if ($zip->open($tempDocxPath, \ZipArchive::CREATE) !== true) {
            $this->fail("Cannot create temporary docx file.");
        }

        // Create a large image to embed
        $largeImage = imagecreatetruecolor(1500, 1000);
        ob_start();
        imagejpeg($largeImage, null, 100);
        $largeImageData = ob_get_clean();
        imagedestroy($largeImage);

        // Add to ZIP under word/media/
        $zip->addFromString('word/media/image1.jpg', $largeImageData);
        // Add a mock document.xml
        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>Hello World</w:t></w:r></w:p></w:body></w:document>');
        $zip->close();

        // Create UploadedFile object
        $file = new UploadedFile($tempDocxPath, 'document.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

        // 2. Upload and optimize
        $result = UploadHelper::processUpload($file, ['docx'], false, 'test-docx', true);

        $this->assertTrue($result['success']);
        $uploadedPath = storage_path('app/private/' . $result['path']);
        $this->assertFileExists($uploadedPath);

        // 3. Open the optimized docx and inspect the image
        $optimizedZip = new \ZipArchive();
        if ($optimizedZip->open($uploadedPath) === true) {
            $optimizedImageData = $optimizedZip->getFromName('word/media/image1.jpg');
            $this->assertNotEmpty($optimizedImageData);

            // Re-read dimensions from memory image
            $optimizedImage = imagecreatefromstring($optimizedImageData);
            $this->assertNotFalse($optimizedImage);

            $width = imagesx($optimizedImage);
            $height = imagesy($optimizedImage);

            $this->assertEquals(1000, $width, "Embedded image width should have been resized to maxDim = 1000 inside docx");
            $this->assertEquals(667, $height, "Embedded image height should maintain ratio inside docx");

            imagedestroy($optimizedImage);
            $optimizedZip->close();
        } else {
            $this->fail("Cannot open optimized docx file.");
        }

        // Clean up
        @unlink($uploadedPath);
        @unlink($tempDocxPath);
    }
}
