<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI | {{ $title }}</title>
    <link href="{{ route('sisgedi.assets.gestor_css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body class="spreadsheet-page">
    <header class="spreadsheet-header">
        <div><span>Documento en vista previa</span><h1>{{ $title }}</h1><strong>Solo lectura</strong></div>
        <a class="primary-button" href="{{ $downloadUrl }}"><i class="fa-solid fa-download"></i> Descargar para editar</a>
    </header>
    <main class="spreadsheet-main">
        <div id="spreadsheet-status" class="spreadsheet-status">Cargando documento...</div>
        <div id="spreadsheet-tabs" class="spreadsheet-tabs"></div>
        <div class="spreadsheet-table-wrap"><table id="spreadsheet-table" class="spreadsheet-table"></table></div>
    </main>
    <script>
        const fileUrl = @json($fileUrl);
        const status = document.getElementById('spreadsheet-status');
        const table = document.getElementById('spreadsheet-table');
        const tabs = document.getElementById('spreadsheet-tabs');
        let workbook;

        function renderSheet(sheetName) {
            const sheet = workbook.Sheets[sheetName];
            const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
            table.innerHTML = '';
            rows.forEach((row, rowIndex) => {
                const tr = document.createElement('tr');
                row.forEach((cell) => {
                    const element = document.createElement(rowIndex === 0 ? 'th' : 'td');
                    element.textContent = cell;
                    tr.appendChild(element);
                });
                table.appendChild(tr);
            });
            document.querySelectorAll('.spreadsheet-tab').forEach((tab) => tab.classList.toggle('is-active', tab.dataset.sheet === sheetName));
            status.textContent = 'Documento abierto en modo solo lectura';
        }

        fetch(fileUrl)
            .then((response) => response.arrayBuffer())
            .then((buffer) => {
                workbook = XLSX.read(buffer, { type: 'array' });
                workbook.SheetNames.forEach((sheetName) => {
                    const tab = document.createElement('button');
                    tab.type = 'button';
                    tab.className = 'spreadsheet-tab';
                    tab.dataset.sheet = sheetName;
                    tab.textContent = sheetName;
                    tab.onclick = () => renderSheet(sheetName);
                    tabs.appendChild(tab);
                });
                renderSheet(workbook.SheetNames[0]);
            })
            .catch(() => { status.textContent = 'No fue posible visualizar este Excel. Usa Descargar para abrirlo.'; });
    </script>
</body>
</html>
