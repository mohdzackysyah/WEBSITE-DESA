<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\LetterTemplate;
use Illuminate\Support\Facades\Storage;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'jenis_surat' => 'domisili',
                'nama_surat' => 'Surat Keterangan Domisili',
            ],
            [
                'jenis_surat' => 'sktm',
                'nama_surat' => 'Surat Keterangan Tidak Mampu (SKTM)',
            ],
            [
                'jenis_surat' => 'pindah',
                'nama_surat' => 'Surat Pindah Penduduk',
            ],
        ];

        // Ensure private/templates directory exists
        $directoryPath = storage_path('app/private/templates');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        foreach ($templates as $t) {
            $fileName = 'templates/' . $t['jenis_surat'] . '.docx';
            $fullPath = storage_path('app/private/' . $fileName);

            // Generate default .docx template programmatically
            $this->generateDefaultDocx($t['jenis_surat'], $t['nama_surat'], $fullPath);

            // Create or update database record
            LetterTemplate::updateOrCreate(
                ['jenis_surat' => $t['jenis_surat']],
                [
                    'nama_surat' => $t['nama_surat'],
                    'file_path' => $fileName,
                ]
            );
        }
    }

    /**
     * Generate default DOCX file structure using PHPWord.
     */
    private function generateDefaultDocx(string $jenis, string $title, string $path): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Define default styles
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'paperSize' => 'A4',
            'marginLeft' => 1440, // 1 inch
            'marginRight' => 1440,
            'marginTop' => 1080, // 0.75 inch
            'marginBottom' => 1080,
        ]);

        // 1. Kop Surat
        $phpWord->addParagraphStyle('kopStyle', ['align' => 'center', 'spaceAfter' => 0]);
        $section->addText('PEMERINTAH KABUPATEN ${kabupaten}', ['size' => 12, 'bold' => true], 'kopStyle');
        $section->addText('KECAMATAN ${kecamatan}', ['size' => 12, 'bold' => true], 'kopStyle');
        $section->addText('KANTOR KEPALA DESA ${nama_desa}', ['size' => 16, 'bold' => true], 'kopStyle');
        $section->addText('Alamat: Kantor Desa ${nama_desa} | Email: desa.penebal@bengkalis.go.id', ['size' => 9, 'italic' => true], 'kopStyle');
        
        // Horizontal border line
        $section->addText('__________________________________________________________________________', ['size' => 11, 'bold' => true], 'kopStyle');
        $section->addText('', [], ['spaceAfter' => 200]);

        // 2. Judul Surat
        $phpWord->addParagraphStyle('titleStyle', ['align' => 'center', 'spaceAfter' => 0]);
        $section->addText(strtoupper($title), ['size' => 12, 'bold' => true, 'underline' => 'single'], 'titleStyle');
        $section->addText('Nomor: ${nomor_surat}', ['size' => 11], 'titleStyle');
        $section->addText('', [], ['spaceAfter' => 300]);

        // 3. Pembuka
        $section->addText('Yang bertanda tangan di bawah ini, Kepala Desa ${nama_desa}, Kecamatan ${kecamatan}, Kabupaten ${kabupaten}, menerangkan dengan sebenarnya bahwa:', ['size' => 11], ['align' => 'both']);
        $section->addText('', [], ['spaceAfter' => 150]);

        // 4. Tabel Biodata Warga
        $tableStyle = [
            'borderSize' => 0,
            'cellMargin' => 60,
        ];
        $table = $section->addTable($tableStyle);
        
        $fields = [
            'Nama Lengkap' => '${nama}',
            'NIK' => '${nik}',
            'Tempat / Tanggal Lahir' => '${tempat_lahir}, ${tanggal_lahir}',
            'Jenis Kelamin' => '${jenis_kelamin}',
            'Agama' => '${agama}',
            'Pekerjaan' => '${pekerjaan}',
            'Alamat Asal' => '${alamat}',
        ];

        foreach ($fields as $label => $var) {
            $table->addRow();
            $table->addCell(2500)->addText($label, ['size' => 11]);
            $table->addCell(400)->addText(':', ['size' => 11]);
            
            $cell = $table->addCell(6000);
            if ($label === 'Nama Lengkap') {
                $cell->addText($var, ['size' => 11, 'bold' => true]);
            } else {
                $cell->addText($var, ['size' => 11]);
            }
        }

        $section->addText('', [], ['spaceAfter' => 250]);

        // 5. Isi Spesifik
        if ($jenis === 'domisili') {
            $section->addText('Menerangkan bahwa nama tersebut di atas adalah benar-benar warga kami yang berdomisili menetap di: ${alamat_domisili}.', ['size' => 11], ['align' => 'both']);
            $section->addText('', [], ['spaceAfter' => 150]);
            $section->addText('Surat keterangan ini diberikan kepada yang bersangkutan untuk dipergunakan sebagai kelengkapan berkas/persyaratan: ${keperluan}.', ['size' => 11], ['align' => 'both']);
        } elseif ($jenis === 'sktm') {
            $section->addText('Menerangkan bahwa keluarga nama tersebut di atas adalah benar warga Desa ${nama_desa} yang berdasarkan peninjauan kami tergolong dalam kategori Keluarga Kurang Mampu (Pra-Sejahtera).', ['size' => 11], ['align' => 'both']);
            $section->addText('', [], ['spaceAfter' => 150]);
            $section->addText('Surat keterangan tidak mampu ini diberikan sebagai kelengkapan administrasi/persyaratan: ${keperluan} pada instansi/lembaga ${nama_sekolah_rs}.', ['size' => 11], ['align' => 'both']);
        } elseif ($jenis === 'pindah') {
            $section->addText('Menerangkan bahwa nama tersebut di atas telah mengajukan permohonan surat pindah penduduk keluar dari Desa ${nama_desa} menuju alamat baru sebagai berikut:', ['size' => 11], ['align' => 'both']);
            $section->addText('', [], ['spaceAfter' => 100]);
            
            // Nested simple table for destination address
            $destTable = $section->addTable($tableStyle);
            
            $destTable->addRow();
            $destTable->addCell(2000)->addText('Alamat Tujuan', ['size' => 11]);
            $destTable->addCell(400)->addText(':', ['size' => 11]);
            $destTable->addCell(6000)->addText('${alamat_tujuan}', ['size' => 11]);
            
            $destTable->addRow();
            $destTable->addCell(2000)->addText('RT/RW Tujuan', ['size' => 11]);
            $destTable->addCell(400)->addText(':', ['size' => 11]);
            $destTable->addCell(6000)->addText('RT ${rt_tujuan} / RW ${rw_tujuan}', ['size' => 11]);
            
            $destTable->addRow();
            $destTable->addCell(2000)->addText('Wilayah Tujuan', ['size' => 11]);
            $destTable->addCell(400)->addText(':', ['size' => 11]);
            $destTable->addCell(6000)->addText('Dusun ${dusun_tujuan}, Desa/Kel. ${desa_tujuan}, Kec. ${kecamatan_tujuan}, Kab. ${kabupaten_tujuan}', ['size' => 11]);
            
            $destTable->addRow();
            $destTable->addCell(2000)->addText('Provinsi Tujuan', ['size' => 11]);
            $destTable->addCell(400)->addText(':', ['size' => 11]);
            $destTable->addCell(6000)->addText('${provinsi_tujuan}', ['size' => 11]);
            
            $section->addText('', [], ['spaceAfter' => 150]);
            $section->addText('Adapun alasan perpindahan penduduk tersebut adalah disebabkan oleh: ${alasan_pindah}.', ['size' => 11], ['align' => 'both']);
        }

        $section->addText('', [], ['spaceAfter' => 200]);

        // 6. Penutup
        $section->addText('Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.', ['size' => 11], ['align' => 'both']);
        $section->addText('', [], ['spaceAfter' => 450]);

        // 7. Tanda Tangan
        $phpWord->addParagraphStyle('sigStyle', ['align' => 'right', 'spaceAfter' => 0]);
        $section->addText('${nama_desa}, ${tanggal_surat}', ['size' => 11], 'sigStyle');
        $section->addText('Kepala Desa ${nama_desa}', ['size' => 11, 'bold' => true], 'sigStyle');
        $section->addText('', [], ['spaceAfter' => 800]); // signature space
        $section->addText('${nama_kepala}', ['size' => 11, 'bold' => true, 'underline' => 'single'], 'sigStyle');

        // Save DOCX file to storage path
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($path);
    }
}
