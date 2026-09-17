<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI | Documento guía</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/solid.css') }}" rel="stylesheet">
    <link href="{{ route('sisgedi.assets.gestor_css') }}" rel="stylesheet">
</head>
<body class="gestor-shell">
    <main class="guide-page">
        <a class="guide-back" href="{{ url()->previous() }}"><i class="fa-solid fa-arrow-left"></i> Volver a tareas</a>
        <section class="guide-card">
            <div class="guide-header"><div><span class="guide-kicker">Documento guía</span><h1>{{ $guide->titulo }}</h1><p>Área: {{ $guide->sector ?: 'Sin área definida' }}</p></div><a class="primary-button" href="{{ route('sisgedi.documento_guia.download', $guide->tarea_id) }}"><i class="fa-solid fa-download"></i> Descargar guía</a></div>
            <div class="guide-grid"><article><h2>Instrucciones</h2><p>{{ $guide->instrucciones }}</p></article><article><h2>Entregables esperados</h2><p>{{ $guide->entregables_esperados }}</p></article><article><h2>Plazos</h2><p>{{ $guide->plazos }}</p></article><article><h2>Fecha límite</h2><p>{{ $guide->fecha_limite ? \Illuminate\Support\Carbon::parse($guide->fecha_limite)->format('d M Y') : 'No definida' }}</p></article></div>
        </section>
    </main>
</body>
</html>
