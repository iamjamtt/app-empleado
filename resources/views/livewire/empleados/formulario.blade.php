<?php

use Livewire\Volt\Component;
use App\Models\{Genero, Area, ModalidadContrato, JornadaLaboral, Empleado};
use Livewire\Attributes\{Title, Validate};
use Mary\Traits\Toast;

new
#[Title('Formulario Empleado')]
class extends Component {
    use Toast;

    // variable para obtener el id del empleado
    public int $id = 0;

    // variable para el modo del formulario | crear, editar
    public string $modo = 'crear';

    // variables del formulario
    #[Validate('required|numeric|digits:8|unique:Empleado,EmpDNI')]
    public string $dni = '';
    #[Validate('required|string|max:100')]
    public string $nombres = '';
    #[Validate('required|string|max:100')]
    public string $apellidoPaterno = '';
    #[Validate('required|string|max:100')]
    public string $apellidoMaterno = '';
    #[Validate('required|date')]
    public string $fechaNacimiento = '';
    #[Validate('required|exists:Genero,GeneroID')]
    public int $genero = 0;
    #[Validate('required|exists:Area,AreaID')]
    public int $area = 0;
    #[Validate('required|exists:ModalidadContrato,ModalidadID')]
    public int $modalidad = 0;
    #[Validate('required|exists:JornadaLaboral,JornadaID')]
    public int $jornada = 0;
    #[Validate('required|date')]
    public string $fechaInicio = '';
    #[Validate('required|date')]
    public string $fechaIngreso = '';

    public function mount($EmpID = null): void
    {
        if ($EmpID) {
            $empleado = Empleado::query()
                ->find($EmpID);

            if (!$empleado) {
                abort(404);
            } else {
                $this->id = $empleado->EmpID;
                $this->modo = 'editar';
                $this->dni = $empleado->EmpDNI;
                $this->nombres = $empleado->EmpNombres;
                $this->apellidoPaterno = $empleado->EmpApellidoPaterno;
                $this->apellidoMaterno = $empleado->EmpApellidoMaterno;
                $this->fechaNacimiento = $empleado->EmpFechaNacimiento;
                $this->genero = $empleado->GeneroID;
                $this->area = $empleado->AreaID;
                $this->modalidad = $empleado->ModalidadID;
                $this->jornada = $empleado->JornadaID;
                $this->fechaInicio = $empleado->EmpFechaInicio;
                $this->fechaIngreso = $empleado->EmpFechaIngreso;
            }
        }
    }

    public function resetFormulario(): void
    {
        $this->reset([
            'dni',
            'nombres',
            'apellidoPaterno',
            'apellidoMaterno',
            'fechaNacimiento',
            'genero',
            'area',
            'modalidad',
            'jornada',
            'fechaInicio',
            'fechaIngreso',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function guardar(): void
    {
        // validar formulario
        if ($this->modo === 'crear') {
            $this->validate();
        } else {
            $this->validate([
                'dni' => 'required|numeric|digits:8|unique:Empleado,EmpDNI,' . $this->id . ',EmpID',
                'nombres' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:100',
                'apellidoMaterno' => 'required|string|max:100',
                'fechaNacimiento' => 'required|date',
                'genero' => 'required|exists:Genero,GeneroID',
                'area' => 'required|exists:Area,AreaID',
                'modalidad' => 'required|exists:ModalidadContrato,ModalidadID',
                'jornada' => 'required|exists:JornadaLaboral,JornadaID',
                'fechaInicio' => 'required|date',
                'fechaIngreso' => 'required|date',
            ]);
        }

        // guardar empleado
        if ($this->modo === 'crear') {
            $empleado = new Empleado();
        } else {
            $empleado = Empleado::query()
                ->find($this->id);
        }
        $empleado->EmpCodigo = $this->generarCodigoEmpleado();
        $empleado->EmpDNI = $this->dni;
        $empleado->EmpNombres = $this->nombres;
        $empleado->EmpApellidoPaterno = $this->apellidoPaterno;
        $empleado->EmpApellidoMaterno = $this->apellidoMaterno;
        $empleado->GeneroID = $this->genero;
        $empleado->AreaID = $this->area;
        $empleado->ModalidadID = $this->modalidad;
        $empleado->JornadaID = $this->jornada;
        $empleado->EmpFechaInicio = $this->fechaInicio;
        $empleado->EmpFechaNacimiento = $this->fechaNacimiento;
        $empleado->EmpFechaIngreso = $this->fechaIngreso;
        $empleado->EmpCorreoElectronico = $this->correoElectronico($this->nombres, $this->apellidoPaterno);
        $empleado->save();

        // redireccionar a la lista de empleados
        $this->success(
            '¡Exito!',
            'Empleado guardado correctamente.',
            redirectTo: '/'
        );
    }

    public function generarCodigoEmpleado(): string
    {
        // obtener el ultimo empleado
        $empleado = Empleado::query()
            ->orderBy('EmpID', 'desc')
            ->first();
        // obtener el codigo del ultimo empleado
        $codigo = $empleado->EmpCodigo ?? 'EMP000';
        // obtener el numero del codigo
        $numero = (int) substr($codigo, 3);
        // incrementar el numero
        $numero++;
        // generar el nuevo codigo
        return 'EMP' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    public function correoElectronico($nombre, $apellidoPaterno): string
    {
        // obtener el primero nombre
        $nombre = explode(' ', $nombre)[0]; // Jamt Mendoza => ['Jamt', 'Mendoza'] => 'Jamt'
        // obtener el apellido sin espacios
        $apellido = str_replace(' ', '', $apellidoPaterno); // Del Aguila => 'DelAguila'
        // convertir a minusculas
        $correo = mb_strtolower($apellido) . '_' . mb_strtolower($nombre) . '@app.empleado.com';
        return $correo;
    }

    public function with(): array
    {
        if ($this->modo === 'crear') {
            $tituloComponente = 'Crear Empleado';
        } else {
            $tituloComponente = 'Editar Empleado - ' . $this->dni;
        }

        $generos = Genero::query()
            ->get();

        $areas = Area::query()
            ->get();

        $modalidades = ModalidadContrato::query()
            ->get();

        $jornadas = JornadaLaboral::query()
            ->get();

        return [
            'tituloComponente' => $tituloComponente,
            'generos' => $generos,
            'areas' => $areas,
            'modalidades' => $modalidades,
            'jornadas' => $jornadas,
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header :title="$tituloComponente" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Regresar" responsive icon="o-arrow-left" class="btn-neutral" link="/" />
        </x-slot:actions>
    </x-header>

    <!-- FORM -->
    <x-form wire:submit="guardar">
        <div class="grid grid-cols-2 gap-4">
            <x-input
                label="DNI"
                wire:model.live="dni"
                inline
            />
            <x-input
                label="Nombres"
                wire:model.live="nombres"
                inline
            />
            <x-input
                label="Apellido Paterno"
                wire:model.live="apellidoPaterno"
                inline
            />
            <x-input
                label="Apellido Materno"
                wire:model.live="apellidoMaterno"
                inline
            />
            <x-input
                label="Fecha de Nacimiento"
                wire:model.live="fechaNacimiento"
                type="date"
                inline
            />
            <x-select
                label="Genero"
                :options="$generos"
                option-value="GeneroID"
                option-label="GeneroNombre"
                placeholder="Seleccione el genero del empleado"
                wire:model.live="genero"
                inline
            />
            <x-select
                label="Area"
                :options="$areas"
                option-value="AreaID"
                option-label="AreaNombre"
                placeholder="Seleccione el area del empleado"
                wire:model.live="area"
                inline
            />
            <x-select
                label="Modalidad de Contrato"
                :options="$modalidades"
                option-value="ModalidadID"
                option-label="ModalidadNombre"
                placeholder="Seleccione la modalidad de contrato del empleado"
                wire:model.live="modalidad"
                inline
            />
            <x-select
                label="Jornada Laboral"
                :options="$jornadas"
                option-value="JornadaID"
                option-label="JornadaNombre"
                placeholder="Seleccione la jornada laboral del empleado"
                wire:model.live="jornada"
                inline
            />
            <div class="grid grid-cols-2 gap-4">
                <x-input
                    label="Fecha de Inicio"
                    wire:model.live="fechaInicio"
                    type="date"
                    inline
                />
                <x-input
                    label="Fecha de Ingreso"
                    wire:model.live="fechaIngreso"
                    type="date"
                    inline
                />
            </div>
        </div>

        <x-slot:actions>
            @if ($modo === 'crear')
                <x-button label="Cancel" wire:click="resetFormulario" />
            @endif
            <x-button label="Guardar" class="btn-primary" type="submit" spinner="guardar" />
        </x-slot:actions>
    </x-form>
</div>
