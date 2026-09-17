<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI | {{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/solid.css') }}" rel="stylesheet">
    <link href="{{ route('sisgedi.assets.gestor_css') }}" rel="stylesheet">
</head>
<body class="gestor-shell">
    <aside class="gestor-sidebar" id="gestor-sidebar">
        <div class="brand-block">
            <div class="brand-mark">SG</div>
            <div><strong>SISGEDI</strong><span>Gestión Integral</span></div>
        </div>
        <nav class="gestor-nav" aria-label="Navegación del Gestor">
            <p class="nav-label">General</p>
            <a class="nav-item {{ $type === 'dashboard' ? 'is-active' : '' }}" href="{{ route($esLider ? 'sisgedi.dashboard.lider' : 'sisgedi.dashboard.gestor') }}"><i class="fa-solid fa-grid-2"></i><span>Dashboard Principal</span></a>
            <p class="nav-label">Tareas y Cascada</p>
            <a class="nav-item {{ $type === 'received' ? 'is-active' : '' }}" href="{{ route('sisgedi.tareas_recibidas') }}"><i class="fa-solid fa-layer-group"></i><span>Tareas Recibidas</span></a>
            @if ($esLider)
                <a class="nav-item {{ $type === 'collaborators' ? 'is-active' : '' }}" href="{{ route('sisgedi.lider.tareas_colaboradores') }}"><i class="fa-solid fa-clipboard-list"></i><span>Tareas para Colaboradores</span></a>
            @else
                <a class="nav-item {{ $type === 'leaders' ? 'is-active' : '' }}" href="{{ route('sisgedi.gestor.tareas_lideres') }}"><i class="fa-solid fa-clipboard-list"></i><span>Tareas para Líderes</span></a>
            @endif
            <p class="nav-label">Evidencias</p>
            <a class="nav-item {{ $type === 'evidence' ? 'is-active' : '' }}" href="{{ route('sisgedi.lider.mis_evidencias') }}"><i class="fa-solid fa-upload"></i><span>Mis Evidencias</span></a>
            @if ($esLider)
                <a class="nav-item {{ $type === 'review' ? 'is-active' : '' }}" href="{{ route('sisgedi.gestor.revision_evidencias') }}"><i class="fa-solid fa-file-circle-check"></i><span>Revisión de Evidencias</span></a>
            @else
                <a class="nav-item {{ $type === 'review' ? 'is-active' : '' }}" href="{{ route('sisgedi.gestor.revision_evidencias') }}"><i class="fa-solid fa-file-circle-check"></i><span>Revisión de Evidencias</span></a>
            @endif
            <button class="nav-item nav-disabled" type="button" disabled><i class="fa-solid fa-file-lines"></i><span>Listado Maestro</span><small>Próximamente</small></button>
            <p class="nav-label">Seguimiento</p>
            <button class="nav-item nav-disabled" type="button" disabled><i class="fa-solid fa-book-open"></i><span>Bitácoras del Sector</span><small>Próximamente</small></button>
            <button class="nav-item nav-disabled" type="button" disabled><i class="fa-solid fa-lightbulb"></i><span>Planeación Autónoma</span><small>Próximamente</small></button>
            <button class="nav-item nav-disabled" type="button" disabled><i class="fa-solid fa-chart-line"></i><span>Reportes</span><small>Próximamente</small></button>
            <button class="nav-item nav-disabled" type="button" disabled><i class="fa-solid fa-calendar-days"></i><span>Cronograma</span><small>Próximamente</small></button>
            <p class="nav-label">Cuenta</p>
            <button class="nav-item nav-disabled" type="button" disabled><i class="fa-solid fa-gear"></i><span>Configuración</span><small>Próximamente</small></button>
        </nav>
        <div class="location-card"><strong>Centro La Angostura</strong><span>Campoalegre, Huila</span></div>
    </aside>

    <div class="gestor-main">
        <header class="gestor-topbar">
            <button class="menu-toggle" type="button" aria-label="Abrir menú" onclick="document.body.classList.toggle('sidebar-open')"><i class="fa-solid fa-bars"></i></button>
            <div class="topbar-actions">
                <div class="notification-menu"><button class="notification-button {{ $notificationCount > 0 ? 'has-notifications' : '' }}" type="button" aria-label="Notificaciones" onclick="toggleGestorMenu('notification-dropdown')"><i class="fa-regular fa-bell"></i>@if ($notificationCount > 0)<b>{{ $notificationCount }}</b>@endif</button><div class="topbar-dropdown notification-dropdown" id="notification-dropdown"><strong>Notificaciones</strong><span>{{ $notificationCount > 0 ? $notificationCount . ' elemento(s) requieren atención.' : 'No tienes notificaciones pendientes.' }}</span></div></div>
                <div class="profile-block" onclick="toggleGestorMenu('profile-dropdown')"><div class="profile-avatar">{{ strtoupper(substr($user['nombre'] ?? 'G', 0, 2)) }}</div><div class="profile-details"><strong>{{ $user['nombre'] ?? 'Usuario' }}</strong><span>{{ $esLider ? 'Líder' : 'Gestor' }}</span></div><button class="profile-trigger" type="button" aria-label="Abrir menú de usuario"><i class="fa-solid fa-chevron-down"></i></button><div class="topbar-dropdown profile-dropdown" id="profile-dropdown" onclick="event.stopPropagation()"><strong>{{ $user['nombre'] ?? 'Usuario' }}</strong><span>{{ $user['correo'] ?? '' }}</span><form action="{{ route('sisgedi.logout') }}" method="POST">@csrf<button type="submit" class="logout-button"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</button></form></div></div>
            </div>
        </header>

        <main class="gestor-content">
            <div class="breadcrumb">Inicio</div>
            <div class="page-heading"><div><h1>{{ $title }}</h1><p>{{ $subtitle }}</p></div><span class="area-badge">{{ $area }}</span></div>
            <section class="module-panel">
                <div class="module-toolbar">
                    <label class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" placeholder="Buscar tarea..."></label>
                    <button class="filter-button" type="button">Estado <i class="fa-solid fa-chevron-down"></i></button>
                    <button class="filter-button" type="button">Fase <i class="fa-solid fa-chevron-down"></i></button>
                    @if ($type === 'leaders')
                        <button class="primary-button" type="button" onclick="openGestorModal('task-modal')"><i class="fa-solid fa-plus"></i> Nueva Tarea para Líderes</button>
                    @elseif ($type === 'collaborators')
                        <button class="primary-button" type="button" onclick="openGestorModal('task-modal')"><i class="fa-solid fa-plus"></i> Nueva Tarea para Colaboradores</button>
                    @elseif ($type === 'evidence')
                        <button class="primary-button" type="button" onclick="openGestorModal('evidence-modal')"><i class="fa-solid fa-upload"></i> Cargar Evidencia</button>
                    @endif
                </div>

                @if ($items->isEmpty())
                    <div class="module-empty"><i class="fa-solid fa-inbox"></i><strong>No hay registros para mostrar</strong><span>La información aparecerá cuando existan datos asociados a este módulo.</span></div>
                @else
                    <div class="module-table-wrap">
                        <table class="module-table">
                            <thead>
                                <tr>
                                    @if (in_array($type, ['evidence', 'review'], true))
                                        <th>CÓDIGO</th><th>TAREA ASOCIADA</th><th>ESTADO</th><th>FECHA DE CARGA</th><th></th>
                                    @else
                                        <th>TAREA / DOCUMENTO GUÍA</th><th>FECHA LÍMITE</th><th>ESTADO</th><th>EVIDENCIA ESPERADA</th><th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $estado = str_replace('_', ' ', $item->estado_individual ?? 'pendiente');
                                        $estadoClase = str_replace(' ', '-', $estado);
                                    @endphp
                                    <tr>
                                        @if (in_array($type, ['evidence', 'review'], true))
                                            <td><strong>EVD-SIS-{{ $item->tarea_asignacion_id }}</strong></td>
                                            <td>Tarea #{{ $item->tarea_id }}</td>
                                            <td><span class="status status-{{ $estadoClase }}">{{ ucfirst($estado) }}</span></td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                        @else
                                            <td><strong>{{ $item->titulo ?? 'Tarea #' . $item->tarea_id }}</strong><small>Asignación #{{ $item->tarea_asignacion_id }}</small></td>
                                            <td>{{ $item->fecha_limite ? \Illuminate\Support\Carbon::parse($item->fecha_limite)->format('d M Y') : \Illuminate\Support\Carbon::parse($item->fecha_asignacion)->format('d M Y') }}</td>
                                            <td><span class="status status-{{ $estadoClase }}">{{ ucfirst($estado) }}</span></td>
                                            <td>{{ $item->evidencia_esperada ?: 'Documento guía' }}</td>
                                        @endif
                                        <td class="row-actions">@if (in_array($type, ['evidence', 'review'], true) && ! empty($item->archivo))<a class="icon-button action-button" href="{{ route('sisgedi.evidencia.show', $item->evidencia_id) }}" aria-label="Ver evidencia" title="Ver evidencia"><i class="fa-solid fa-eye"></i><span>Ver</span></a><a class="icon-button action-button" href="{{ route('sisgedi.evidencia.download', $item->evidencia_id) }}" aria-label="Descargar evidencia" title="Descargar evidencia"><i class="fa-solid fa-download"></i><span>Descargar</span></a>@elseif (! in_array($type, ['evidence', 'review'], true) && ! empty($item->archivo_url))<a class="icon-button action-button" href="{{ route('sisgedi.documento_guia.show', $item->tarea_id) }}" aria-label="Ver documento guía" title="Ver documento guía"><i class="fa-solid fa-eye"></i><span>Ver</span></a><a class="icon-button action-button" href="{{ route('sisgedi.documento_guia.download', $item->tarea_id) }}" aria-label="Descargar documento guía" title="Descargar documento guía"><i class="fa-solid fa-download"></i><span>Descargar</span></a>@else<span class="no-guide">Sin archivo</span>@endif</td>
                                    </tr>
                                    @if ($type === 'review')
                                        <tr class="review-actions-row"><td colspan="5"><form class="review-actions-form" method="POST" action="{{ route('sisgedi.gestor.revision_evidencias.rechazar') }}">@csrf<input type="hidden" name="evidencia_id" value="{{ $item->evidencia_id }}"><input name="motivo_rechazo" placeholder="Motivo de rechazo (obligatorio si rechazas)"><button class="review-approve" type="submit" formaction="{{ route('sisgedi.gestor.revision_evidencias.aprobar') }}">Aprobar evidencia</button><button class="review-reject" type="submit">Rechazar con motivo</button></form></td></tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </main>
    </div>
    <div class="gestor-modal" id="task-modal" aria-hidden="true" onclick="if (event.target === this) closeGestorModal('task-modal')">
        <div class="gestor-modal-card">
            <button class="modal-close" type="button" onclick="closeGestorModal('task-modal')" aria-label="Cerrar">Cerrar <i class="fa-solid fa-xmark"></i></button>
            <h2>{{ $type === 'collaborators' ? 'Nueva Tarea para Colaboradores' : 'Nueva Tarea para Líderes' }}</h2>
            <p>Registra la tarea y asígnala a un usuario de `users_sisgedi`.</p>
            <form action="{{ $type === 'collaborators' ? route('sisgedi.lider.tareas_colaboradores.store') : route('sisgedi.gestor.tareas_lideres.store') }}" method="POST" enctype="multipart/form-data" class="gestor-form">
                @csrf
                <label>Título<input name="titulo" required maxlength="255"></label>
                <label>Descripción<textarea name="descripcion" rows="3"></textarea></label>
                <div class="form-columns"><label>Fecha límite<input type="date" name="fecha_limite" required></label><label>{{ $type === 'collaborators' ? 'Colaborador de tu equipo' : 'Líder de tu área' }}<select name="colaborador_usuario_id" required><option value="">Selecciona {{ $type === 'collaborators' ? 'un colaborador' : 'un líder' }}</option>@if ($type === 'collaborators')@forelse ($colaboradores as $colaborador)<option value="{{ $colaborador->id_users }}">{{ $colaborador->nombre }} - {{ $colaborador->rol }}</option>@empty<option value="" disabled>No hay colaboradores registrados</option>@endforelse @else @forelse ($lideres as $lider)<option value="{{ $lider->id_users }}">{{ $lider->nombre }} - {{ $lider->rol }}</option>@empty<option value="" disabled>No hay líderes registrados en tu área</option>@endforelse @endif</select></label></div>
                <div class="form-columns"><label>Área asignada<input name="sector" value="{{ $area }}" readonly></label><label>Evidencia esperada<input name="evidencia_esperada" maxlength="150"></label></div>
                <label>Instrucciones para el líder<textarea name="instrucciones" rows="3" required placeholder="Explica paso a paso qué debe realizar."></textarea></label>
                <div class="form-columns"><label>Entregables esperados<textarea name="entregables_esperados" rows="3" required placeholder="Indica qué debe entregar."></textarea></label><label>Plazos<textarea name="plazos" rows="3" required placeholder="Describe fechas o condiciones."></textarea></label></div>
                <label>Documento guía<input type="file" name="documento_guia" required accept=".pdf,.doc,.docx,.xls,.xlsx"></label>
                <button class="modal-submit" type="submit">Crear tarea</button>
            </form>
        </div>
    </div>
    <div class="gestor-modal" id="evidence-modal" aria-hidden="true" onclick="if (event.target === this) closeGestorModal('evidence-modal')">
        <div class="gestor-modal-card">
            <button class="modal-close" type="button" onclick="closeGestorModal('evidence-modal')" aria-label="Cerrar">Cerrar <i class="fa-solid fa-xmark"></i></button>
            <h2>Cargar Evidencia</h2>
            <p>La evidencia quedará asociada a una asignación existente.</p>
            <div class="modal-area"><i class="fa-solid fa-location-dot"></i> Área: <strong>{{ $area }}</strong></div>
            <form action="{{ route('sisgedi.lider.evidencias.store') }}" method="POST" enctype="multipart/form-data" class="gestor-form">
                @csrf
                <label>Asignación<select name="tarea_asignacion_id" required><option value="">Selecciona una asignación</option>@forelse ($asignacionesDisponibles as $asignacion)<option value="{{ $asignacion->tarea_asignacion_id }}">{{ $asignacion->titulo }} - Asignación #{{ $asignacion->tarea_asignacion_id }}</option>@empty<option value="" disabled>No hay asignaciones en {{ $area }}</option>@endforelse</select></label>
                <label>Archivo<input type="file" name="archivo" required></label>
                <button class="modal-submit" type="submit">Cargar evidencia</button>
            </form>
        </div>
    </div>
    <script>
        function toggleGestorMenu(id) { document.querySelectorAll('.topbar-dropdown').forEach((menu) => { if (menu.id !== id) menu.classList.remove('is-open'); }); document.getElementById(id).classList.toggle('is-open'); }
        document.addEventListener('click', function (event) { if (!event.target.closest('.notification-menu, .profile-block')) document.querySelectorAll('.topbar-dropdown').forEach((menu) => menu.classList.remove('is-open')); });
        function openGestorModal(id) { document.getElementById(id).classList.add('is-open'); document.getElementById(id).setAttribute('aria-hidden', 'false'); }
        function closeGestorModal(id) { document.getElementById(id).classList.remove('is-open'); document.getElementById(id).setAttribute('aria-hidden', 'true'); }
        document.addEventListener('keydown', function (event) { if (event.key === 'Escape') document.querySelectorAll('.gestor-modal.is-open').forEach((modal) => closeGestorModal(modal.id)); });
    </script>
</body>
</html>
