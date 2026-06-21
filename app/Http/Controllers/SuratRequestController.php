<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use App\Models\Resident;
use App\Models\SuratRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Dompdf\Options;

class SuratRequestController extends Controller
{
    // ==========================================
    // PORTAL WARGA (PUBLIC)
    // ==========================================

    public function showLayanan()
    {
        return view('public.layanan');
    }

    public function showForm($type)
    {
        $allowedTypes = ['domisili', 'sktm', 'pindah'];
        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }

        $title = match ($type) {
            'domisili' => 'Surat Keterangan Domisili',
            'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)',
            'pindah' => 'Surat Pindah Penduduk',
        };

        return view('public.layanan_form', compact('type', 'title'));
    }

    public function checkNik(Request $request)
    {
        $request->validate(['nik' => 'required']);
        $resident = Resident::where('nik', $request->nik)->first();

        if ($resident) {
            return response()->json([
                'success' => true,
                'resident' => [
                    'nama_lengkap' => $resident->nama_lengkap,
                    'tempat_lahir' => $resident->tempat_lahir,
                    'tanggal_lahir' => $resident->tanggal_lahir,
                    'jenis_kelamin' => $resident->jenis_kelamin,
                    'alamat' => $resident->alamat,
                    'rt' => $resident->rt,
                    'rw' => $resident->rw,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'NIK tidak terdaftar dalam database kependudukan desa. Pengajuan manual memerlukan waktu verifikasi lebih lama.'
        ]);
    }

    public function store(Request $request, $type)
    {
        $rules = [
            'nik' => 'required|numeric|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'berkas_pendukung' => 'required|file', // No maximum limit for citizens; optimized automatically by UploadHelper
        ];

        // Specific form validations
        if ($type === 'domisili') {
            $rules['keperluan'] = 'required|string|max:255';
            $rules['alamat_domisili'] = 'required|string';
        } elseif ($type === 'sktm') {
            $rules['keperluan'] = 'required|string|max:255';
            $rules['nama_sekolah_rs'] = 'required|string|max:255';
            $rules['penghasilan_orang_tua'] = 'required|numeric';
        } elseif ($type === 'pindah') {
            $rules['alamat_tujuan'] = 'required|string';
            $rules['rt_tujuan'] = 'required|numeric';
            $rules['rw_tujuan'] = 'required|numeric';
            $rules['dusun_tujuan'] = 'required|string|max:255';
            $rules['desa_tujuan'] = 'required|string|max:255';
            $rules['kecamatan_tujuan'] = 'required|string|max:255';
            $rules['kabupaten_tujuan'] = 'required|string|max:255';
            $rules['provinsi_tujuan'] = 'required|string|max:255';
            $rules['alasan_pindah'] = 'required|string|max:255';
        }

        $request->validate($rules);

        // Upload berkas pendukung secara aman
        $uploadResult = UploadHelper::processUpload($request->file('berkas_pendukung'));
        if (!$uploadResult['success']) {
            return back()->withErrors(['berkas_pendukung' => implode(' ', $uploadResult['errors'])])->withInput();
        }

        // Siapkan form_data spesifik
        $formData = [];
        if ($type === 'domisili') {
            $formData = $request->only(['keperluan', 'alamat_domisili']);
        } elseif ($type === 'sktm') {
            $formData = $request->only(['keperluan', 'nama_sekolah_rs', 'penghasilan_orang_tua']);
        } elseif ($type === 'pindah') {
            $formData = $request->only([
                'alamat_tujuan', 'rt_tujuan', 'rw_tujuan', 'dusun_tujuan',
                'desa_tujuan', 'kecamatan_tujuan', 'kabupaten_tujuan', 'provinsi_tujuan', 'alasan_pindah'
            ]);
        }

        // Hasilkan Kode Pelacakan Unik (misal: DSA-2026-3C4F5A)
        $year = date('Y');
        $randomHex = strtoupper(Str::random(6));
        $trackingCode = "DSA-{$year}-{$randomHex}";

        // Simpan pengajuan
        $suratRequest = SuratRequest::create([
            'nomor_pengajuan' => $trackingCode,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_surat' => $type,
            'form_data' => $formData,
            'status' => 'menunggu_verifikasi',
            'berkas_pendukung' => $uploadResult['path'],
        ]);

        return redirect()->route('layanan.lacak')->with([
            'success_code' => $trackingCode,
            'search_nik' => $request->nik
        ]);
    }

    public function showLacak(Request $request)
    {
        $surat = null;
        $searched = false;

        if ($request->has('kode_pelacakan') && $request->has('nik')) {
            $request->validate([
                'kode_pelacakan' => 'required|string',
                'nik' => 'required|numeric'
            ]);

            $surat = SuratRequest::where('nomor_pengajuan', $request->kode_pelacakan)
                ->where('nik', $request->nik)
                ->first();
            $searched = true;
        }

        return view('public.lacak', compact('surat', 'searched'));
    }

    public function downloadFinal($id)
    {
        $surat = SuratRequest::findOrFail($id);

        if ($surat->status !== 'selesai' || !$surat->dokumen_final) {
            abort(403, 'Dokumen surat belum siap diunduh.');
        }

        $filePath = storage_path('app/private/' . $surat->dokumen_final);
        if (!file_exists($filePath)) {
            abort(404, 'File surat tidak ditemukan di server.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Surat_Selesai_' . str_replace('/', '_', $surat->nomor_surat) . '.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    /**
     * Menampilkan halaman penampil PDF untuk surat final.
     * PDF di-embed sebagai base64 data URL di dalam halaman HTML
     * agar download manager (IDM) tidak bisa mengintercept.
     */
    public function previewFinal($id)
    {
        $surat = SuratRequest::findOrFail($id);

        if ($surat->status !== 'selesai' || !$surat->dokumen_final) {
            abort(403, 'Dokumen surat belum siap.');
        }

        $filePath = storage_path('app/private/' . $surat->dokumen_final);
        if (!file_exists($filePath)) {
            abort(404, 'File surat tidak ditemukan di server.');
        }

        $jenisSurat = match($surat->jenis_surat) {
            'domisili' => 'Surat Keterangan Domisili',
            'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)',
            'pindah' => 'Surat Pindah Penduduk',
            default => $surat->jenis_surat
        };

        $pdfBase64 = base64_encode(file_get_contents($filePath));

        return view('pdf_viewer', [
            'pdfBase64' => $pdfBase64,
            'title' => $jenisSurat . ' — ' . $surat->nomor_surat,
        ]);
    }


    // ==========================================
    // OPERATOR PANEL (DASHBOARD)
    // ==========================================

    public function adminDashboard(Request $request)
    {
        $query = SuratRequest::orderBy('created_at', 'desc');

        // Filter status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $term = '%' . $request->search . '%';
            $query->where(function($q) use ($term) {
                $q->where('nomor_pengajuan', 'like', $term)
                  ->orWhere('nama_lengkap', 'like', $term)
                  ->orWhere('nik', 'like', $term);
            });
        }

        $requests = $query->paginate(10);
        return view('admin.surat_list', compact('requests'));
    }

    public function showDetail($id)
    {
        $surat = SuratRequest::findOrFail($id);
        return view('admin.surat_detail', compact('surat'));
    }

    public function downloadAttachment($id)
    {
        $surat = SuratRequest::findOrFail($id);
        $filePath = storage_path('app/private/' . $surat->berkas_pendukung);

        if (!file_exists($filePath)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $mime = mime_content_type($filePath);
        return response()->file($filePath, ['Content-Type' => $mime]);
    }

    public function updateStatus(Request $request, $id)
    {
        $surat = SuratRequest::findOrFail($id);
        $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'catatan_operator' => 'nullable|string',
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string',
            'dokumen_final' => 'required_if:status,selesai|nullable|file|mimes:pdf',
        ]);

        $status = $request->status;
        $surat->status = $status;
        $surat->catatan_operator = $request->catatan_operator;

        if ($status === 'diproses') {
            // Generate nomor surat otomatis jika belum ada
            if (!$surat->nomor_surat) {
                $count = SuratRequest::whereNotNull('nomor_surat')->count() + 1;
                $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
                $romanMonths = [
                    1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI',
                    7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
                ];
                $month = $romanMonths[date('n')];
                $year = date('Y');
                $surat->nomor_surat = "470/{$sequence}/DSA/{$month}/{$year}";
            }
        } elseif ($status === 'ditolak') {
            $surat->alasan_penolakan = $request->alasan_penolakan;
        } elseif ($status === 'selesai') {
            if ($request->hasFile('dokumen_final')) {
                // Upload PDF bertanda tangan secara aman
                $uploadResult = UploadHelper::processUpload($request->file('dokumen_final'), ['pdf']);
                if (!$uploadResult['success']) {
                    return back()->withErrors(['dokumen_final' => implode(' ', $uploadResult['errors'])]);
                }
                $surat->dokumen_final = $uploadResult['path'];
            }
        }

        $surat->save();

        return redirect()->route('admin.surat.detail', $id)->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function generateDraft($id)
    {
        $surat = SuratRequest::findOrFail($id);
        
        // Buat nomor surat jika belum ada
        if (!$surat->nomor_surat) {
            $count = SuratRequest::whereNotNull('nomor_surat')->count() + 1;
            $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
            $romanMonths = [
                1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI',
                7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
            ];
            $month = $romanMonths[date('n')];
            $year = date('Y');
            $surat->nomor_surat = "470/{$sequence}/DSA/{$month}/{$year}";
            $surat->save();
        }

        // Coba generate dari Word (.docx) template terlebih dahulu
        $pdfOutput = $this->generatePdfFromWord($surat);

        // Fallback ke HTML template jika gagal atau file .docx tidak ada
        if ($pdfOutput === null) {
            $namaDesa = Setting::get('nama_desa', 'Desa Penebal');
            $kecamatan = Setting::get('kecamatan', 'Kecamatan Bengkalis');
            $kabupaten = Setting::get('kabupaten', 'Kabupaten Bengkalis');
            $namaKepala = Setting::get('nama_kepala', 'M. Sani');

            // Cari detail warga berdasarkan NIK jika terdaftar
            $resident = Resident::where('nik', $surat->nik)->first();
            
            // Layout HTML untuk draf surat
            $html = view('admin.pdf_template', compact('surat', 'resident', 'namaDesa', 'kecamatan', 'kabupaten', 'namaKepala'))->render();

            // Setup Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
        }

        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="DRAF_' . str_replace('/', '_', $surat->nomor_surat) . '.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    /**
     * Menampilkan halaman penampil PDF untuk draf surat.
     * PDF di-generate oleh Dompdf lalu di-embed sebagai base64 data URL.
     */
    public function previewDraft($id)
    {
        $surat = SuratRequest::findOrFail($id);

        // Buat nomor surat jika belum ada
        if (!$surat->nomor_surat) {
            $count = SuratRequest::whereNotNull('nomor_surat')->count() + 1;
            $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
            $romanMonths = [
                1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI',
                7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
            ];
            $month = $romanMonths[date('n')];
            $year = date('Y');
            $surat->nomor_surat = "470/{$sequence}/DSA/{$month}/{$year}";
            $surat->save();
        }

        // Coba generate dari Word (.docx) template terlebih dahulu
        $pdfOutput = $this->generatePdfFromWord($surat);

        // Fallback ke HTML template jika gagal atau file .docx tidak ada
        if ($pdfOutput === null) {
            $namaDesa = Setting::get('nama_desa', 'Desa Penebal');
            $kecamatan = Setting::get('kecamatan', 'Kecamatan Bengkalis');
            $kabupaten = Setting::get('kabupaten', 'Kabupaten Bengkalis');
            $namaKepala = Setting::get('nama_kepala', 'M. Sani');
            $resident = Resident::where('nik', $surat->nik)->first();

            $html = view('admin.pdf_template', compact('surat', 'resident', 'namaDesa', 'kecamatan', 'kabupaten', 'namaKepala'))->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
        }

        $pdfBase64 = base64_encode($pdfOutput);

        $jenisSurat = match($surat->jenis_surat) {
            'domisili' => 'Surat Keterangan Domisili',
            'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)',
            'pindah' => 'Surat Pindah Penduduk',
            default => $surat->jenis_surat
        };

        return view('pdf_viewer', [
            'pdfBase64' => $pdfBase64,
            'title' => 'DRAF — ' . $jenisSurat . ' — ' . ($surat->nomor_surat ?? 'Baru'),
        ]);
    }

    /**
     * Helper method to generate PDF from DOCX templates using PHPWord and Dompdf.
     * Returns binary PDF output or null if template file/record does not exist.
     */
    private function generatePdfFromWord($surat)
    {
        $template = \App\Models\LetterTemplate::where('jenis_surat', $surat->jenis_surat)->first();
        if (!$template || !$template->file_path) {
            return null;
        }

        $filePath = storage_path('app/private/' . $template->file_path);
        if (!file_exists($filePath)) {
            return null;
        }

        $namaDesa = Setting::get('nama_desa', 'Desa Penebal');
        $kecamatan = Setting::get('kecamatan', 'Kecamatan Bengkalis');
        $kabupaten = Setting::get('kabupaten', 'Kabupaten Bengkalis');
        $namaKepala = Setting::get('nama_kepala', 'M. Sani');
        
        $resident = Resident::where('nik', $surat->nik)->first();

        // 1. Load DOCX template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filePath);

        // 2. Set global variables
        $templateProcessor->setValue('nomor_surat', $surat->nomor_surat ?? 'Baru');
        $templateProcessor->setValue('nama', $surat->nama_lengkap);
        $templateProcessor->setValue('nik', $surat->nik);
        
        $tempatLahir = $resident ? $resident->tempat_lahir : 'Bogor';
        $tanggalLahir = \Carbon\Carbon::parse($resident ? $resident->tanggal_lahir : now())->translatedFormat('d F Y');
        $templateProcessor->setValue('tempat_lahir', $tempatLahir);
        $templateProcessor->setValue('tanggal_lahir', $tanggalLahir);
        
        $jenisKelamin = $resident ? ($resident->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan') : 'Laki-Laki';
        $templateProcessor->setValue('jenis_kelamin', $jenisKelamin);
        
        $templateProcessor->setValue('agama', $resident ? $resident->agama : 'Islam');
        $templateProcessor->setValue('pekerjaan', $resident ? $resident->pekerjaan : 'Petani');
        
        $alamat = $resident ? $resident->alamat : 'Rt 01 Rw 01 Dusun Krajan, Desa Makmur';
        $templateProcessor->setValue('alamat', $alamat);
        
        $templateProcessor->setValue('nama_desa', $namaDesa);
        $templateProcessor->setValue('kecamatan', $kecamatan);
        $templateProcessor->setValue('kabupaten', $kabupaten);
        $templateProcessor->setValue('nama_kepala', $namaKepala);
        
        $tanggalSurat = \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('d F Y');
        $templateProcessor->setValue('tanggal_surat', $tanggalSurat);

        // 3. Set specific form variables
        if (is_array($surat->form_data) || is_object($surat->form_data)) {
            foreach ($surat->form_data as $key => $value) {
                if ($key === 'penghasilan_orang_tua') {
                    $value = 'Rp ' . number_format($value, 0, ',', '.');
                }
                $templateProcessor->setValue($key, (string)$value);
            }
        }

        // 4. Save to a temporary docx file
        $tempDir = storage_path('app/private/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            $tempDocx = $tempDir . '/' . uniqid('docx_') . '.docx';
            $templateProcessor->saveAs($tempDocx);

            // 5. Convert temporary docx to HTML
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempDocx);
            
            // Resolve paragraph alignments from style repository
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $this->resolveElementStyle($element);
                }
            }

            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $tempHtml = $tempDir . '/' . uniqid('html_') . '.html';
            $htmlWriter->save($tempHtml);
            $htmlContent = file_get_contents($tempHtml);

            // 6. Clean underscores Kop line
            $htmlContent = preg_replace(
                '/<p[^>]*>[^<]*_{20,}[^<]*<\/p>/iu',
                '<div style="border-bottom: 3px double #000; margin-top: 5px; margin-bottom: 20px; width: 100%;"></div>',
                $htmlContent
            );
            $htmlContent = preg_replace(
                '/<p[^>]*>\s*<span[^>]*>[^<]*_{20,}[^<]*<\/span>\s*<\/p>/iu',
                '<div style="border-bottom: 3px double #000; margin-top: 5px; margin-bottom: 20px; width: 100%;"></div>',
                $htmlContent
            );

            // Overrides table borders and formatting in head
            $customStyle = "
<style>
body {
    font-family: 'Helvetica', 'Arial', sans-serif !important;
    font-size: 12px !important;
    line-height: 1.5 !important;
    padding: 10px 20px !important;
}
table {
    border: 0 !important;
    border-collapse: collapse !important;
    width: 100% !important;
    margin-bottom: 10px !important;
}
td {
    border: 0 !important;
    padding: 3px 6px !important;
    vertical-align: top !important;
}
p {
    margin: 0 0 6px 0 !important;
}
.kopStyle {
    text-align: center !important;
}
</style>
</head>
";
            $htmlContent = str_replace('</head>', $customStyle, $htmlContent);

            // 7. Render HTML to PDF via Dompdf
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
            return $pdfOutput;
        } catch (\Exception $e) {
            \Log::warning("Word-to-PDF conversion failed: " . $e->getMessage());
            return null;
        } finally {
            // 8. Clean up temporary files
            if (isset($tempDocx) && file_exists($tempDocx)) {
                @unlink($tempDocx);
            }
            if (isset($tempHtml) && file_exists($tempHtml)) {
                @unlink($tempHtml);
            }
        }
    }

    /**
     * Recursively resolve paragraph style alignments from the global Style repository.
     * This is necessary because PHPWord reader loads styles into Style repository
     * but fails to copy the alignment properties to the elements, causing text
     * alignment to be lost when converting to HTML/PDF.
     */
    private function resolveElementStyle($element)
    {
        if (method_exists($element, 'getParagraphStyle')) {
            $pStyle = $element->getParagraphStyle();
            if ($pStyle instanceof \PhpOffice\PhpWord\Style\Paragraph) {
                $styleName = $pStyle->getStyleName();
                if ($styleName) {
                    $registeredStyle = \PhpOffice\PhpWord\Style::getStyle($styleName);
                    if ($registeredStyle instanceof \PhpOffice\PhpWord\Style\Paragraph) {
                        if ('' === $pStyle->getAlignment() && '' !== $registeredStyle->getAlignment()) {
                            $pStyle->setAlignment($registeredStyle->getAlignment());
                        }
                    }
                }
            }
        }

        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $this->resolveElementStyle($child);
            }
        } elseif (method_exists($element, 'getRows')) {
            foreach ($element->getRows() as $row) {
                foreach ($row->getCells() as $cell) {
                    foreach ($cell->getElements() as $child) {
                        $this->resolveElementStyle($child);
                    }
                }
            }
        }
    }
}
