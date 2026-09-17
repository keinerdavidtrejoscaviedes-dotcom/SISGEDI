<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI | Dashboard del Gestor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ route('sisgedi.assets.gestor_css') }}" rel="stylesheet">
</head>
<body class="gestor-shell">
    <aside class="gestor-sidebar" id="gestor-sidebar">
        <div class="brand-block">
            <div class="brand-mark">SG</div>
            <div>
                <strong>SISGEDI</strong>
                <span>Gestión Integral</span>
            </div>
        </div>

        <nav class="gestor-nav" aria-label="Navegación del Gestor">
            <p class="nav-label">General</p>
            <a class="nav-item is-active" href="{{ route('sisgedi.dashboard.gestor') }}">
                <i class="fa-solid fa-grid-2"></i><span>Dashboard Principal</span>
            </a>

            <p class="nav-label">Tareas y Cascada</p>
            <a class="nav-item" href="{{ route('sisgedi.tareas_recibidas') }}">
                <i class="fa-solid fa-layer-group"></i><span>Tareas Recibidas</span>
            </a>
            <a class="nav-item" href="{{ route('sisgedi.gestor.tareas_lideres') }}">
                <i class="fa-solid fa-clipboard-list"></i><span>Tareas para Líderes</span>
            </a>

            <p class="nav-label">Evidencias</p>
            <a class="nav-item" href="{{ route('sisgedi.lider.mis_evidencias') }}">
                <i class="fa-solid fa-upload"></i><span>Mis Evidencias</span>
            </a>
            <a class="nav-item" href="{{ route('sisgedi.gestor.revision_evidencias') }}">
                <i class="fa-solid fa-file-circle-check"></i><span>Revisión de Evidencias</span>
            </a>
            <button class="nav-item nav-disabled" type="button" disabled>
                <i class="fa-solid fa-file-lines"></i><span>Listado Maestro</span><small>Próximamente</small>
            </button>

            <p class="nav-label">Seguimiento</p>
            <button class="nav-item nav-disabled" type="button" disabled>
                <i class="fa-solid fa-book-open"></i><span>Bitácoras del Sector</span><small>Próximamente</small>
            </button>
            <button class="nav-item nav-disabled" type="button" disabled>
                <i class="fa-solid fa-lightbulb"></i><span>Planeación Autónoma</span><small>Próximamente</small>
            </button>
            <button class="nav-item nav-disabled" type="button" disabled>
                <i class="fa-solid fa-chart-line"></i><span>Reportes</span><small>Próximamente</small>
            </button>
            <button class="nav-item nav-disabled" type="button" disabled>
                <i class="fa-solid fa-calendar-days"></i><span>Cronograma</span><small>Próximamente</small>
            </button>

            <p class="nav-label">Cuenta</p>
            <button class="nav-item nav-disabled" type="button" disabled>
                <i class="fa-solid fa-gear"></i><span>Configuración</span><small>Próximamente</small>
            </button>
        </nav>

        <div class="location-card">
            <strong>Centro La Angostura</strong>
            <span>Campoalegre, Huila</span>
        </div>
    </aside>

    <div class="gestor-main">
        <header class="gestor-topbar">
            <button class="menu-toggle" type="button" aria-label="Abrir menú" onclick="document.body.classList.toggle('sidebar-open')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="topbar-actions">
                <div class="notification-menu">
                    <button class="notification-button {{ $notificationCount > 0 ? 'has-notifications' : '' }}" type="button" aria-label="Notificaciones" onclick="toggleGestorMenu('notification-dropdown')">
                        <i class="fa-regular fa-bell"></i>
                        @if ($notificationCount > 0)<b>{{ $notificationCount }}</b>@endif
                    </button>
                    <div class="topbar-dropdown notification-dropdown" id="notification-dropdown">
                        <strong>Notificaciones</strong>
                        <span>{{ $notificationCount > 0 ? $notificationCount . ' elemento(s) requieren atención.' : 'No tienes notificaciones pendientes.' }}</span>
                    </div>
                </div>
                <div class="profile-block" onclick="toggleGestorMenu('profile-dropdown')">
                    <div class="profile-avatar">{{ strtoupper(substr($user['nombre'] ?? 'G', 0, 2)) }}</div>
                    <div class="profile-details">
                        <strong>{{ $user['nombre'] ?? 'Gestor' }}</strong>
                        <span>Gestor</span>
                    </div>
                    <button class="profile-trigger" type="button" aria-label="Abrir menú de usuario"><i class="fa-solid fa-chevron-down"></i></button>
                    <div class="topbar-dropdown profile-dropdown" id="profile-dropdown" onclick="event.stopPropagation()">
                        <strong>{{ $user['nombre'] ?? 'Gestor' }}</strong>
                        <span>{{ $user['correo'] ?? '' }}</span>
                        <form action="{{ route('sisgedi.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-button"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="gestor-content">
            <div class="breadcrumb">Inicio</div>
            <div class="page-heading">
                <div>
                    <h1>Dashboard Principal</h1>
                    <p>Resumen de la gestión de tu sector</p>
                </div>
                <div class="heading-badges"><span class="area-badge">{{ $area }}</span><span class="role-badge">{{ $user['rol'] ?? 'Gestor' }}</span></div>
            </div>

            <section class="metrics-grid" aria-label="Resumen del Gestor">
                <article class="metric-card metric-green">
                    <div class="metric-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <div><span>Tareas recibidas</span><strong>{{ $totalTareas }}</strong></div>
                    <small>Total registrado</small>
                </article>
                <article class="metric-card metric-blue">
                    <div class="metric-icon"><i class="fa-solid fa-clock"></i></div>
                    <div><span>Tareas pendientes</span><strong>{{ $tareasPendientes }}</strong></div>
                    <small>Por gestionar</small>
                </article>
                <article class="metric-card metric-amber">
                    <div class="metric-icon"><i class="fa-solid fa-file-circle-check"></i></div>
                    <div><span>Evidencias en revisión</span><strong>{{ $evidenciasRevision }}</strong></div>
                    <small>Requieren atención</small>
                </article>
                <article class="metric-card metric-purple">
                    <div class="metric-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <div><span>Planes de trabajo</span><strong>{{ $planesTrabajo }}</strong></div>
                    <small>Publicados por ti</small>
                </article>
            </section>

            <section class="dashboard-grid">
                <article class="panel panel-large">
                    <div class="panel-heading">
                        <div><h2>Tareas recibidas</h2><p>Asignaciones registradas para tu gestión</p></div>
                        <a href="{{ route('sisgedi.tareas_recibidas') }}" class="panel-link">Ver todas <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    @if ($totalTareas === 0)
                        <div class="empty-state"><i class="fa-solid fa-inbox"></i><strong>No hay tareas registradas</strong><span>Las tareas asignadas aparecerán aquí.</span></div>
                    @else
                        <div class="status-summary">
                            <div><span class="status-dot dot-blue"></span><strong>{{ $tareasPendientes }}</strong><small>En proceso</small></div>
                            <div><span class="status-dot dot-amber"></span><strong>{{ $evidenciasRevision }}</strong><small>En revisión</small></div>
                            <div><span class="status-dot dot-green"></span><strong>{{ max(0, $totalTareas - $tareasPendientes) }}</strong><small>Finalizadas</small></div>
                        </div>
                    @endif
                </article>

                <article class="panel">
                    <div class="panel-heading"><div><h2>Próximos hitos</h2><p>Calendario de tus planes</p></div></div>
                    @forelse ($proximosHitos as $hito)
                        <div class="milestone"><span class="milestone-date">{{ \Illuminate\Support\Carbon::parse($hito->fecha)->format('d M') }}</span><div><strong>{{ $hito->titulo }}</strong><small>{{ ucfirst($hito->tipo) }}</small></div></div>
                    @empty
                        <div class="empty-state compact"><i class="fa-regular fa-calendar"></i><span>No hay hitos próximos.</span></div>
                    @endforelse
                </article>
            </section>
        </main>
    </div>
    <script>
        function toggleGestorMenu(id) {
            document.querySelectorAll('.topbar-dropdown').forEach((menu) => {
                if (menu.id !== id) menu.classList.remove('is-open');
            });
            document.getElementById(id).classList.toggle('is-open');
        }
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.notification-menu, .profile-block')) {
                document.querySelectorAll('.topbar-dropdown').forEach((menu) => menu.classList.remove('is-open'));
            }
        });
    </script>
</body>
</html>
