<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dokumen PDF' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #525659;
        }
        embed {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    {{-- PDF di-embed sebagai base64 data URL. Tidak ada request HTTP terpisah untuk file PDF,
         sehingga download manager (IDM, dll) tidak bisa mengintercept. --}}
    <embed src="data:application/pdf;base64,{{ $pdfBase64 }}" type="application/pdf">
</body>
</html>
