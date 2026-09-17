{{-- Sidebar del panel operativo (Gerente Administrativo y vistas compartidas) --}}
<aside class="w-64 flex-shrink-0 text-white flex flex-col" style="background:#0D3320;">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color: rgba(255,255,255,.08);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black flex-shrink-0"
             style="background:#39A900;">
            SG
        </div>
        <div>
            <p class="font-bold leading-none">SISGEDI</p>
            <p class="text-[11px] leading-none mt-1" style="color: rgba(255,255,255,.5);">Gestión integral</p>
        </div>
    </div>

    {{-- Navegación --}}
    <nav class="flex-1 px-3 py-5 space-y-1">
        @php
            $navItems = [
                ['route' => 'sisgedi.gerente.dashboard', 'pattern' => 'sisgedi.gerente.dashboard', 'icon' => 'fa-table-columns', 'label' => 'Dashboard'],
                ['route' => 'sisgedi.gerente.tareas.index', 'pattern' => 'sisgedi.gerente.tareas.*', 'icon' => 'fa-diagram-project', 'label' => 'Tareas en Cascada'],
                ['route' => 'sisgedi.gerente.plan-trabajo.edit', 'pattern' => 'sisgedi.gerente.plan-trabajo.*', 'icon' => 'fa-calendar-days', 'label' => 'Plan de Trabajo'],
                ['route' => 'sisgedi.gerente.instructores.index', 'pattern' => 'sisgedi.gerente.instructores.*', 'icon' => 'fa-chalkboard-user', 'label' => 'Instructores'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $activo = Route::is($item['pattern']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $activo ? 'text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}"
               style="{{ $activo ? 'background:#39A900;' : '' }}">
                <i class="fas {{ $item['icon'] }} w-4 text-center"></i>
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="pt-4 mt-4 border-t" style="border-color: rgba(255,255,255,.08);">
            <p class="px-3 text-[10px] font-semibold uppercase tracking-wide text-white/30 mb-2">Vista compartida</p>
            <a href="{{ route('sisgedi.plan-trabajo.show') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-colors">
                <i class="fas fa-eye w-4 text-center"></i>
                Cronograma de la fase
            </a>
        </div>
    </nav>

    {{-- Pie --}}
    <div class="px-5 py-4 text-[11px] text-white/40 border-t" style="border-color: rgba(255,255,255,.08);">
        <p class="font-semibold text-white/60">Centro La Angostura</p>
        <p>Campoalegre - Huila</p>
    </div>
</aside>
