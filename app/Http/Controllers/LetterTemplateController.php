<?php

namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LetterTemplateController extends Controller
{
    public function index()
    {
        $templates = LetterTemplate::all();
        return view('admin.templates_index', compact('templates'));
    }

    public function download($jenis)
    {
        $template = LetterTemplate::where('jenis_surat', $jenis)->firstOrFail();
        $filePath = storage_path('app/private/' . $template->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }

        return response()->download($filePath, $template->jenis_surat . '_template.docx');
    }

    public function store(Request $request, $jenis)
    {
        $template = LetterTemplate::where('jenis_surat', $jenis)->firstOrFail();

        $request->validate([
            'template_file' => 'required|file',
        ]);

        $file = $request->file('template_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension !== 'docx') {
            return back()->withErrors(['template_file' => 'File harus memiliki ekstensi .docx (Microsoft Word).']);
        }

        // Simpan file baru dan timpa file lama
        $fileName = 'templates/' . $template->jenis_surat . '.docx';
        $destinationPath = storage_path('app/private/templates');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $template->jenis_surat . '.docx');

        // Update database record path if needed
        $template->file_path = $fileName;
        $template->save();

        return redirect()->route('admin.surat.templates.index')->with('success', "Template {$template->nama_surat} berhasil diperbarui.");
    }

    public function preview($jenis)
    {
        $template = LetterTemplate::where('jenis_surat', $jenis)->firstOrFail();
        $filePath = storage_path('app/private/' . $template->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }

        $tempDir = storage_path('app/private/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
            
            // Resolve paragraph alignments from style repository
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $this->resolveElementStyle($element);
                }
            }

            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $tempHtml = $tempDir . '/' . uniqid('tmpl_html_') . '.html';
            $htmlWriter->save($tempHtml);
            $htmlContent = file_get_contents($tempHtml);

            // Clean underscores Kop line
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

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
            $pdfBase64 = base64_encode($pdfOutput);

            return view('pdf_viewer', [
                'pdfBase64' => $pdfBase64,
                'title' => 'Pratinjau Template — ' . $template->nama_surat,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['template_file' => 'Gagal membuat pratinjau template: ' . $e->getMessage()]);
        } finally {
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
