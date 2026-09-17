<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI | Dashboard del Líder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ route('sisgedi.assets.gestor_css') }}" rel="stylesheet">
</head>
<body class="gestor-shell">
    <aside class="gestor-sidebar">
        <div class="brand-block"><div class="brand-mark">SG</div><div><strong>SISGEDI</strong><span>Gestión Integral</span></div></div>
        <nav class="gestor-nav" aria-label="Navegación del Líder">
            <p class="nav-label">General</p>
            <a class="nav-item is-active" href="{{ route('sisgedi.dashboard.lider') }}"><i class="fa-solid fa-grid-2"></i><span>Dashboard Principal</span></a>
            <p class="nav-label">Tareas y Cascada</p>
            <a class="nav-item" href="{{ route('sisgedi.tareas_recibidas') }}"><i class="fa-solid fa-layer-group"></i><span>Tareas Recibidas</span></a>
            <a class="nav-item" href="{{ route('sisgedi.lider.tareas_colaboradores') }}"><i class="fa-solid fa-clipboard-list"></i><span>Tareas para Colaboradores</span></a>
            <p class="nav-label">Evidencias</p>
            <a class="nav-item" href="{{ route('sisgedi.lider.mis_evidencias') }}"><i class="fa-solid fa-upload"></i><span>Mis Evidencias</span></a>
            <a class="nav-item" href="{{ route('sisgedi.gestor.revision_evidencias') }}"><i class="fa-solid fa-file-circle-check"></i><span>Revisión de Evidencias</span></a>
            <a class="nav-item nav-disabled" href="#" onclick="return false"><i class="fa-solid fa-file-lines"></i><span>Listado Maestro</span><small>Próximamente</small></a>
            <p class="nav-label">Seguimiento</p>
            <a class="nav-item nav-disabled" href="#" onclick="return false"><i class="fa-solid fa-book-open"></i><span>Bitácoras del Equipo</span><small>Próximamente</small></a>
            <a class="nav-item nav-disabled" href="#" onclick="return false"><i class="fa-solid fa-lightbulb"></i><span>Plan de Innovación</span><small>Próximamente</small></a>
            <a class="nav-item nav-disabled" href="#" onclick="return false"><i class="fa-solid fa-calendar-days"></i><span>Cronograma</span><small>Próximamente</small></a>
            <p class="nav-label">Cuenta</p>
            <a class="nav-item nav-disabled" href="#" onclick="return false"><i class="fa-solid fa-gear"></i><span>Configuración</span><small>Próximamente</small></a>
        </nav>
        <div class="location-card"><strong>Centro La Angostura</strong><span>Campoalegre, Huila</span></div>
    </aside>

    <div class="gestor-main">
        <header class="gestor-topbar">
            <button class="menu-toggle" type="button" aria-label="Abrir menú"><i class="fa-solid fa-bars"></i></button>
            <div class="topbar-actions">
                <div class="notification-menu"><button class="notification-button {{ $notificationCount > 0 ? 'has-notifications' : '' }}" type="button" aria-label="Notificaciones"><span class="notification-symbol">!</span>@if ($notificationCount > 0)<b>{{ $notificationCount }}</b>@endif</button></div>
                <div class="profile-block"><div class="profile-avatar">{{ strtoupper(substr($user['nombre'] ?? 'L', 0, 2)) }}</div><div class="profile-details"><strong>{{ $user['nombre'] }}</strong><span>Líder</span></div><form action="{{ route('sisgedi.logout') }}" method="POST" class="leader-logout">@csrf<button type="submit" aria-label="Cerrar sesión"><i class="fa-solid fa-right-from-bracket"></i></button></form></div>
            </div>
        </header>

        <main class="gestor-content">
            <div class="breadcrumb">Inicio</div>
            <div class="page-heading"><div><h1>Dashboard Principal</h1><p>Resumen de la gestión de tu equipo</p></div><div class="heading-badges"><span class="area-badge">{{ $area }}</span><span class="role-badge">{{ $user['rol'] }}</span></div></div>
            <section class="metrics-grid" aria-label="Resumen del Líder">
                <article class="metric-card metric-green"><div class="metric-icon"><i class="fa-solid fa-users"></i></div><div><span>Colaboradores activos</span><strong>{{ $colaboradoresActivos }}</strong></div><small>De tu equipo</small></article>
                <article class="metric-card metric-blue"><div class="metric-icon"><i class="fa-solid fa-clipboard-list"></i></div><div><span>Tareas del equipo</span><strong>{{ $asignaciones->count() }}</strong></div><small>Asignadas a ti</small></article>
                <article class="metric-card metric-amber"><div class="metric-icon"><i class="fa-solid fa-file-circle-check"></i></div><div><span>Evidencias pendientes</span><strong>{{ $evidencias->whereIn('estado', ['enviada', 'en_revision'])->count() }}</strong></div><small>Para seguimiento</small></article>
                <article class="metric-card metric-purple"><div class="metric-icon"><i class="fa-solid fa-chart-line"></i></div><div><span>Mi progreso</span><strong>{{ $asignaciones->count() ? round(($asignaciones->where('estado_individual', 'aprobada')->count() / $asignaciones->count()) * 100) : 0 }}%</strong></div><small>Tareas aprobadas</small></article>
            </section>
            <section class="dashboard-grid">
                <article class="panel panel-large"><div class="panel-heading"><div><h2>Tareas recibidas</h2><p>Tareas asignadas por el Gestor de tu sector</p></div><a href="{{ route('sisgedi.tareas_recibidas') }}" class="panel-link">Ver todas <i class="fa-solid fa-arrow-right"></i></a></div>@if ($asignaciones->isEmpty())<div class="empty-state"><i class="fa-solid fa-inbox"></i><strong>No tienes tareas asignadas</strong><span>Las tareas de tu Gestor aparecerán aquí.</span></div>@else<div class="status-summary"><div><span class="status-dot dot-blue"></span><strong>{{ $tareasPendientes }}</strong><small>Pendientes</small></div><div><span class="status-dot dot-amber"></span><strong>{{ $evidencias->whereIn('estado', ['enviada', 'en_revision'])->count() }}</strong><small>Evidencias</small></div><div><span class="status-dot dot-green"></span><strong>{{ $asignaciones->where('estado_individual', 'aprobada')->count() }}</strong><small>Aprobadas</small></div></div>@endif</article>
                <article class="panel"><div class="panel-heading"><div><h2>Mi área</h2><p>Sector asignado</p></div></div><div class="area-summary"><i class="fa-solid fa-location-dot"></i><strong>{{ $area }}</strong><span>{{ $user['rol'] }}</span></div></article>
            </section>
        </main>
    </div>
</body>
</html>
