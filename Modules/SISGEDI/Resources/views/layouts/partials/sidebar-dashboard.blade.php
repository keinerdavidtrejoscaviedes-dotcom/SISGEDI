@php
    $sesion   = session('sisgedi_user');
    // Verificar si existen fases en la BD para mostrar/ocultar el módulo de convocatorias
    $hayFases = \Illuminate\Support\Facades\DB::table('fase')->exists();

    // Fase activa (RN-010): mientras exista una fase con estado "Activa",
    // se oculta la opción de "Crear Fase" en el menú.
    \Modules\SISGEDI\Http\Controllers\FaseController::sincronizarEstados();
    $faseVigenteSidebar = \Illuminate\Support\Facades\DB::getSchemaBuilder()->hasColumn('fase', 'estado')
        ? \Illuminate\Support\Facades\DB::table('fase')->where('estado', 'Activa')->first()
        : null;
@endphp

{{-- Overlay móvil --}}
<div id="dashboardOverlay"
     class="fixed inset-0 bg-black/60 z-20 hidden lg:hidden"
     onclick="toggleDashboardSidebar()"></div>

{{-- ══════════════════════════════════════════════════
     SIDEBAR — réplica exacta de la imagen
══════════════════════════════════════════════════ --}}
<aside id="dashboardSidebar"
       class="w-60 flex-shrink-0 flex flex-col h-screen
              fixed top-0 left-0 z-30
              -translate-x-full lg:translate-x-0
              transition-transform duration-300"
       style="background:#1a2e1a;">

    {{-- ── Logo + nombre sistema ── --}}
    <div class="flex items-center gap-3 px-4 py-4"
         style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 font-black text-sm"
             style="background:#39A900; color:#fff;">
            SG
        </div>
        <div>
            <p class="font-bold text-white text-sm leading-tight">SISGEDI</p>
            <p class="text-xs leading-tight" style="color:rgba(255,255,255,0.5);">Gestión Integral</p>
        </div>
    </div>

    {{-- ── Menú de navegación ── --}}
    <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5 text-[13px]">

        {{-- Dashboard Principal --}}
        <a href="{{ route('sisgedi.dashboard') }}"
           class="sb-item {{ Route::is('sisgedi.dashboard') ? 'sb-active' : '' }}">
            <i class="fas fa-th-large sb-icon"></i>
            Dashboard Principal
        </a>

        {{-- Gestión de Usuarios --}}
        <div x-data="{ open: false }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-users sb-icon"></i>
                <span class="flex-1">Gestión de Usuarios</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-transition class="pl-8 mt-0.5 space-y-0.5">
                <a href="#" class="sb-sub">Listado de Usuarios</a>
                <a href="#" class="sb-sub">Crear Usuario</a>
            </div>
        </div>

        {{-- Sectores Productivos --}}
        <div x-data="{ open: false }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-map-marker-alt sb-icon"></i>
                <span class="flex-1">Sectores Productivos</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
        </div>

        {{-- Gestión de Fases --}}
        <div x-data="{ open: {{ Route::is('sisgedi.fases.*') ? 'true' : 'false' }} }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-calendar-alt sb-icon"></i>
                <span class="flex-1">Gestión de Fases</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-transition class="pl-8 mt-0.5 space-y-0.5">
                <a href="{{ route('sisgedi.fases.index') }}"
                   class="sb-sub {{ Route::is('sisgedi.fases.index') ? 'sb-sub-active' : '' }}">
                    Listado de Fases
                </a>
                @if(! $faseVigenteSidebar)
                <a href="{{ route('sisgedi.fases.create') }}"
                   class="sb-sub {{ Route::is('sisgedi.fases.create') ? 'sb-sub-active' : '' }}">
                    Crear Fase
                </a>
                @endif
            </div>
        </div>


        {{-- Convocatorias y Selección — solo visible si hay al menos una fase creada --}}
        @if($hayFases)
        <div x-data="{ open: {{ (Route::is('sisgedi.convocatorias.*') || Route::is('sisgedi.entrevistas.*')) ? 'true' : 'false' }} }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-bullhorn sb-icon"></i>
                <span class="flex-1">Convocatorias y<br>Selección</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-transition class="pl-8 mt-0.5 space-y-0.5">
                <a href="{{ route('sisgedi.convocatorias.index') }}"
                   class="sb-sub {{ Route::is('sisgedi.convocatorias.index') ? 'sb-sub-active' : '' }}">
                    Lista de Convocatorias
                </a>
                <a href="{{ route('sisgedi.convocatorias.create') }}"
                   class="sb-sub {{ Route::is('sisgedi.convocatorias.create') ? 'sb-sub-active' : '' }}">
                    Crear Convocatoria
                </a>
                <a href="{{ route('sisgedi.convocatorias.resultados') }}"
                   class="sb-sub {{ Route::is('sisgedi.convocatorias.resultados') ? 'sb-sub-active' : '' }}">
                    Resultados Selección
                </a>
                <a href="{{ route('sisgedi.convocatorias.reasignacion') }}"
                   class="sb-sub {{ Route::is('sisgedi.convocatorias.reasignacion') ? 'sb-sub-active' : '' }}">
                    Reasignación
                </a>
                <a href="{{ route('sisgedi.convocatorias.checklist') }}"
                   class="sb-sub {{ Route::is('sisgedi.convocatorias.checklist') ? 'sb-sub-active' : '' }}">
                    <i class="fas fa-check-square mr-1 text-[10px]"></i>Checklist Entrevista
                </a>
                <a href="{{ route('sisgedi.entrevistas.index') }}"
                   class="sb-sub {{ Route::is('sisgedi.entrevistas.*') ? 'sb-sub-active' : '' }}">
                    <i class="fas fa-user-check mr-1 text-[10px]"></i>Realizar Entrevistas
                </a>
            </div>
        </div>
        @else
        {{-- Aviso: se necesita crear una fase primero --}}
        <div class="mx-2 my-1 px-3 py-2.5 rounded-lg" style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2);">
            <div class="flex items-start gap-2">
                <i class="fas fa-lock text-red-400 text-xs mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-[11px] font-semibold text-red-300 leading-tight">Convocatorias bloqueadas</p>
                    <p class="text-[10px] text-red-400/70 mt-0.5 leading-tight">
                        Crea una fase primero en<br>
                        <a href="{{ route('sisgedi.fases.create') }}"
                           class="text-red-300 hover:text-white underline font-semibold">
                            Gestión de Fases
                        </a>
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Listado Maestro Documental --}}
        <div x-data="{ open: false }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-file-alt sb-icon"></i>
                <span class="flex-1">Listado Maestro<br>Documental</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
        </div>

        {{-- Reportes --}}
        <div x-data="{ open: false }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-chart-bar sb-icon"></i>
                <span class="flex-1">Reportes</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
        </div>

        {{-- Auditoría --}}
        <a href="#" class="sb-item">
            <i class="fas fa-shield-alt sb-icon"></i>
            Auditoría
        </a>

        {{-- Paz y Salvo --}}
        <div x-data="{ open: false }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-file-signature sb-icon"></i>
                <span class="flex-1">Paz y Salvo</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
        </div>

        {{-- Configuración --}}
        <div x-data="{ open: false }">
            <button @click="open=!open" class="sb-item w-full text-left">
                <i class="fas fa-cog sb-icon"></i>
                <span class="flex-1">Configuración</span>
                <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                   :class="open ? 'rotate-90' : ''"></i>
            </button>
        </div>

    </nav>

    {{-- ── Footer del sidebar — aviso SICEFA ── --}}
    <div class="px-4 py-3" style="background:#0f1f0f; border-top:1px solid rgba(255,255,255,0.06);">
        <a href="{{ route('home') }}"
           class="flex items-center gap-2 text-xs font-semibold rounded-lg px-3 py-2.5 transition-colors"
           style="background:rgba(57,169,0,0.15); color:#62E31D;">
            <i class="fas fa-th-large text-xs"></i>
            Centro Agroindustrial<br>Campoalegre, Huila
        </a>
    </div>

</aside>

<style>
    .sb-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.45rem;
        color: rgba(255,255,255,0.65);
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        cursor: pointer;
        background: transparent;
        border: none;
        width: 100%;
        line-height: 1.3;
    }
    .sb-item:hover { background: rgba(57,169,0,0.18); color: #fff; }
    .sb-active    { background: rgba(57,169,0,0.28) !important; color: #fff !important; font-weight: 600; }
    .sb-icon      { width: 1.1rem; text-align: center; font-size: 0.8rem; flex-shrink: 0; color: rgba(255,255,255,0.5); }
    .sb-sub {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.6rem;
        border-radius: 0.35rem;
        font-size: 0.76rem;
        color: rgba(255,255,255,0.5);
        text-decoration: none;
        transition: color 0.15s, background 0.15s;
    }
    .sb-sub:hover { color: #fff; background: rgba(255,255,255,0.05); }
    .sb-sub-active { color: #fff !important; background: rgba(57,169,0,0.15) !important; font-weight: 600; }
</style>

<!-- Alpine.js para los dropdowns del sidebar -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
