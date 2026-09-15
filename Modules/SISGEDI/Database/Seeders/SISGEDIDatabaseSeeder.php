<?php

namespace Modules\SISGEDI\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\SICA\Entities\Person;
use Modules\SISGEDI\Entities\Cargo;
use Modules\SISGEDI\Entities\Fase;
use Modules\SISGEDI\Entities\Gerencia;
use Modules\SISGEDI\Entities\Rol;
use Modules\SISGEDI\Entities\SectorProductivo;
use Modules\SISGEDI\Entities\UsuarioRol;

class SISGEDIDatabaseSeeder extends Seeder
{
    /**
     * Contador de documento ficticio, para dar un numero unico y obviamente
     * de prueba a cada Persona que se cree (requerido por la tabla central "people").
     */
    private int $documentoSecuencial = 900000000;

    /**
     * Organigrama real de SENA Empresa, tomado de la tabla users_sisgedi ya
     * preparada por el equipo (nickname, correo y contraseña "Colombia2026*"
     * identicos a los de esa tabla).
     *
     * Alcance: este seeder solo crea la Gerencia Administrativa (la que
     * corresponde a este subgrupo). Las gerencias de Producción y Comercial
     * las gestiona un companero en su propio avance, para evitar choques
     * de datos entre subgrupos del mismo proyecto.
     *
     * NOTA: "lider.sig" queda bajo el area "ASIG" (Gestor ASIG). Confirmado con
     * el catalogo roles_sisgedi: "Gestor ASIG" = sistemas integrados de gestion
     * y calidad, y "Líder Equipo SIG" = Sistema Integrado de Gestión y calidad;
     * es la misma area con abreviatura distinta entre gestor y lider.
     */
    private array $nombreCargoGerente = [
        'Gerencia Administrativa' => 'Gerente Administrativo',
    ];

    private array $organigrama = [
        'Gerencia Administrativa' => [
            'gerente' => 'gerente.administrativo',
            'areas' => [
                'talento' => 'Talento Humano',
                'contabilidad' => 'Contabilidad y Finanzas',
                'asig' => 'ASIG',
                'investigacion' => 'Investigación',
                'innovacion' => 'Innovación y Prototipado',
            ],
            'lideres' => [
                'talento' => 'talento',
                'sig' => 'asig',
            ],
            'lideres_sin_gestor' => [],
        ],
    ];

    public function run(): void
    {
        $this->limpiarDatosDePruebaAnteriores();

        $this->crearCatalogoDeRoles();

        $fase = Fase::updateOrCreate(
            ['nombre' => 'Sol 2026-II'],
            [
                'fecha_inicio' => now()->startOfMonth(),
                'fecha_fin' => now()->addMonths(3)->endOfMonth(),
                'estado' => 'activa',
            ]
        );

        // Cuenta "gerente.general": rol Administrador, permanente, sin gerencia ni fase.
        $this->crearUsuarioConRol(
            nickname: 'gerente.general',
            nombreRol: 'Administrador',
            cargo: null,
            fase: null
        );

        foreach ($this->organigrama as $nombreGerencia => $config) {
            $gerencia = Gerencia::updateOrCreate(
                ['nombre' => $nombreGerencia],
                ['descripcion' => "Gerencia {$nombreGerencia} de SENA Empresa."]
            );

            // Cargo y usuario del Gerente cabeza de la gerencia.
            $cargoGerente = Cargo::updateOrCreate(
                ['nombre' => $this->nombreCargoGerente[$nombreGerencia], 'gerencia_id' => $gerencia->id],
                ['tipo_cargo' => 'GERENTE_ADMINISTRATIVO', 'cargo_superior_id' => null]
            );

            $this->crearUsuarioConRol(
                nickname: $config['gerente'],
                nombreRol: 'GerenteAdministrativo',
                cargo: $cargoGerente,
                fase: $fase
            );

            // Un sector productivo por cada area/gestor de la gerencia, activo en la fase vigente.
            $cargosGestorPorArea = [];
            foreach ($config['areas'] as $clave => $nombreArea) {
                $sector = SectorProductivo::updateOrCreate(
                    ['nombre' => $nombreArea, 'gerencia_id' => $gerencia->id],
                    ['descripcion' => "Área de {$nombreArea} bajo {$nombreGerencia}.", 'estado_catalogo' => 'activo']
                );

                $fase->sectores()->syncWithoutDetaching([$sector->id => ['estado_activo' => true]]);

                $cargoGestor = Cargo::updateOrCreate(
                    ['nombre' => "Gestor de {$nombreArea}", 'gerencia_id' => $gerencia->id],
                    ['tipo_cargo' => 'GESTOR', 'cargo_superior_id' => $cargoGerente->id]
                );

                $cargosGestorPorArea[$clave] = $cargoGestor;

                $this->crearUsuarioConRol(
                    nickname: "gestor.{$clave}",
                    nombreRol: 'Gestor',
                    cargo: $cargoGestor,
                    fase: $fase
                );
            }

            // Lideres que reportan a un gestor de area ya creado.
            foreach ($config['lideres'] as $nickSufijo => $claveArea) {
                $cargoGestor = $cargosGestorPorArea[$claveArea];
                $nombreArea = $config['areas'][$claveArea];

                $cargoLider = Cargo::updateOrCreate(
                    ['nombre' => "Líder de {$nombreArea}", 'gerencia_id' => $gerencia->id],
                    ['tipo_cargo' => 'LIDER', 'cargo_superior_id' => $cargoGestor->id]
                );

                $this->crearUsuarioConRol(
                    nickname: "lider.{$nickSufijo}",
                    nombreRol: 'Lider',
                    cargo: $cargoLider,
                    fase: $fase
                );
            }

            // Lideres sin un gestor de area identificado todavia (ver nota en $organigrama).
            foreach ($config['lideres_sin_gestor'] as $nickSufijo => $nombreArea) {
                $cargoLider = Cargo::updateOrCreate(
                    ['nombre' => "Líder de {$nombreArea}", 'gerencia_id' => $gerencia->id],
                    ['tipo_cargo' => 'LIDER', 'cargo_superior_id' => $cargoGerente->id]
                );

                $this->crearUsuarioConRol(
                    nickname: "lider.{$nickSufijo}",
                    nombreRol: 'Lider',
                    cargo: $cargoLider,
                    fase: $fase
                );
            }
        }
    }

    /**
     * Elimina los datos de prueba genericos de una version anterior de este
     * seeder (usuarios/sectores inventados), ya reemplazados por el
     * organigrama real de users_sisgedi.
     */
    private function limpiarDatosDePruebaAnteriores(): void
    {
        foreach (['gerente.demo@sisgedi.test', 'gestor.demo@sisgedi.test'] as $correo) {
            $usuario = User::withTrashed()->where('email', $correo)->first();
            if ($usuario) {
                $personId = $usuario->person_id;
                UsuarioRol::where('user_id', $usuario->id)->delete();
                $usuario->forceDelete();
                Person::withTrashed()->where('id', $personId)->forceDelete();
            }
        }

        SectorProductivo::whereIn('nombre', ['Producción Porcina', 'Producción Agrícola'])->forceDelete();

        // Reinicia por completo los cargos/sectores de las gerencias que administra
        // este seeder, para poder ajustar nombres/jerarquia (p. ej. "Contabilidad" ->
        // "Contabilidad y Finanzas", o reubicar lider.sig bajo Gestor ASIG) sin dejar
        // registros huerfanos de una version anterior del organigrama.
        $gerenciasGestionadas = Gerencia::whereIn('nombre', array_keys($this->organigrama))->get();

        foreach ($gerenciasGestionadas as $gerencia) {
            $cargoIds = Cargo::where('gerencia_id', $gerencia->id)->pluck('id');
            $sectorIds = SectorProductivo::where('gerencia_id', $gerencia->id)->pluck('id');

            UsuarioRol::whereIn('cargo_id', $cargoIds)->update(['cargo_id' => null]);
            DB::table('sisgedi_fase_sector')->whereIn('sector_id', $sectorIds)->delete();
            Cargo::whereIn('id', $cargoIds)->forceDelete();
            SectorProductivo::whereIn('id', $sectorIds)->forceDelete();
        }
    }

    private function crearCatalogoDeRoles(): void
    {
        $roles = [
            ['nombre_rol' => 'Administrador', 'es_dinamico' => false, 'descripcion' => 'Supervisor general del sistema. No genera tareas, audita y configura.'],
            ['nombre_rol' => 'GerenteAdministrativo', 'es_dinamico' => true, 'descripcion' => 'Cabeza mayor operativa que origina la cascada de tareas hacia los Gestores.'],
            ['nombre_rol' => 'Gestor', 'es_dinamico' => true, 'descripcion' => 'Recibe tareas del Gerente y genera tareas para los Lideres de su sector.'],
            ['nombre_rol' => 'Lider', 'es_dinamico' => true, 'descripcion' => 'Ultimo nivel que asigna tareas directamente a los Colaboradores.'],
            ['nombre_rol' => 'Colaborador', 'es_dinamico' => true, 'descripcion' => 'Ejecuta tareas y sube evidencias. Se activa automaticamente con su Lider.'],
            ['nombre_rol' => 'Instructor', 'es_dinamico' => false, 'descripcion' => 'Valida y firma entregables del paz y salvo.'],
            ['nombre_rol' => 'Aprendiz', 'es_dinamico' => false, 'descripcion' => 'Estado base antes de postularse a un cargo operativo.'],
        ];

        foreach ($roles as $rol) {
            Rol::updateOrCreate(['nombre_rol' => $rol['nombre_rol']], $rol);
        }
    }

    /**
     * Crea (o reutiliza) la Persona + Usuario central para un nickname de
     * users_sisgedi, y activa su rol SISGEDI correspondiente.
     *
     * Contraseña identica a la de users_sisgedi ("Colombia2026*"), pero
     * almacenada con el hash de la tabla central "users" (nunca en texto plano).
     */
    private function crearUsuarioConRol(string $nickname, string $nombreRol, ?Cargo $cargo, ?Fase $fase): User
    {
        $correo = str_replace('.', '_', $nickname).'@sisgedi.sena.edu.co';

        $this->documentoSecuencial++;

        $persona = Person::firstOrCreate(
            ['document_type' => 'Cédula de ciudadanía', 'document_number' => $this->documentoSecuencial],
            [
                'first_name' => $nickname,
                'first_last_name' => 'SISGEDI',
                'eps_id' => 0,
                'population_group_id' => 22,
                'pension_entity_id' => 1,
            ]
        );

        $usuario = User::updateOrCreate(
            ['email' => $correo],
            ['nickname' => $nickname, 'person_id' => $persona->id, 'password' => 'Colombia2026*']
        );

        $rol = Rol::where('nombre_rol', $nombreRol)->firstOrFail();

        UsuarioRol::updateOrCreate(
            ['user_id' => $usuario->id, 'sisgedi_rol_id' => $rol->id, 'fase_id' => $fase?->id],
            [
                'cargo_id' => $cargo?->id,
                'estado' => 'activo',
                'fecha_activacion' => now(),
                'activado_por' => null,
                'activacion_automatica' => false,
            ]
        );

        return $usuario;
    }
}
