<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI | {{ $title }}</title>
    <link href="{{ route('sisgedi.assets.gestor_css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.8.0/mammoth.browser.min.js"></script>
</head>
<body class="spreadsheet-page">
    <header class="spreadsheet-header">
        <div><span>Documento en vista previa</span><h1>{{ $title }}</h1><strong>Solo lectura</strong></div>
        <a class="primary-button" href="{{ $downloadUrl }}">Descargar para editar</a>
    </header>
    <main class="word-main">
        <div id="word-status" class="spreadsheet-status">Cargando documento...</div>
        <article id="word-content" class="word-content"></article>
    </main>
    <script>
        fetch(@json($fileUrl))
            .then((response) => response.arrayBuffer())
            .then((buffer) => mammoth.convertToHtml({ arrayBuffer: buffer }))
            .then((result) => {
                document.getElementById('word-content').innerHTML = result.value;
                document.getElementById('word-status').textContent = 'Documento abierto en modo solo lectura';
            })
            .catch(() => { document.getElementById('word-status').textContent = 'No fue posible visualizar este Word. Usa Descargar para abrirlo.'; });
    </script>
</body>
</html>
