<?php

use App\Models\{Empleado, Operacion};
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\{Title, Validate};

new
#[Title('Gestion de Empleados')]
class extends Component {
    use Toast;

    public string $search = '';

    public $empleado;

    // Modal
    public bool $modal = false;
    public string $titleModal = '';
    public string $subtitleModal = '';
    public string $buttonModal = '';
    public string $accionModal = '';

    // Modal Alerta
    public bool $modalAlerta = false;
    public string $titleModalAlerta = '';
    public string $subtitleModalAlerta = '';
    public string $textModalAlerta = '';
    public string $buttonModalAlerta = '';
    public string $actionModalAlerta = '';

    // Formulario de Beneficios
    #[Validate('required')]
    public string $beneficio = '';
    #[Validate('required|numeric')]
    public $monto;
    #[Validate('required|array')]
    public array $mesesSeleccionados = [];

    // Formulario de Bono
    #[Validate('required|numeric')]
    public $montoBono;
    #[Validate('required|numeric')]
    public $mesesBono;

    // Formulario de Boleta
    #[Validate('required|numeric')]
    public $mesesBoleta;

    public function calcularEdad($EmpCodigo)
    {
        $empleado = Empleado::query()
            ->where('EmpDNI', $EmpCodigo)
            ->first();

        $fechaNacimiento = new DateTime($empleado->EmpFechaNacimiento);
        $edad = $fechaNacimiento->diff(new DateTime(date('Y-m-d')));
        $años = $edad->y;
        $meses = $edad->m;
        $dias = $edad->d;

        $this->modalAlerta = true;
        $this->titleModalAlerta = 'Calcular Edad';
        $this->subtitleModalAlerta = 'Empleado: ' . $empleado->EmpApellidoPaterno . ' ' . $empleado->EmpApellidoMaterno . ', ' . $empleado->EmpNombres;
        $this->textModalAlerta = 'Edad: ' . $años . ' años, ' . $meses . ' meses y ' . $dias . ' días.';
        $this->buttonModalAlerta = 'Aceptar';
        $this->actionModalAlerta = 'cerrarModalAlerta';
    }

    public function calcularAntiguedadEmpresa($EmpCodigo)
    {
        $empleado = Empleado::query()
            ->where('EmpDNI', $EmpCodigo)
            ->first();

        $fechaIngreso = new DateTime($empleado->EmpFechaIngreso);
        $antiguedad = $fechaIngreso->diff(new DateTime(date('Y-m-d')));
        $años = $antiguedad->y;
        $meses = $antiguedad->m;
        $dias = $antiguedad->d;

        $this->modalAlerta = true;
        $this->titleModalAlerta = 'Calcular Antiguedad de Empresa';
        $this->subtitleModalAlerta = 'Empleado: ' . $empleado->EmpApellidoPaterno . ' ' . $empleado->EmpApellidoMaterno . ', ' . $empleado->EmpNombres;
        $this->textModalAlerta = 'Antiguedad: ' . $años . ' años, ' . $meses . ' meses y ' . $dias . ' días.';
        $this->buttonModalAlerta = 'Aceptar';
        $this->actionModalAlerta = 'cerrarModalAlerta';
    }

    public function cerrarModalAlerta()
    {
        $this->modalAlerta = false;
    }

    public function asignarBeneficios(Empleado $empleado)
    {
        $this->reset();

        $this->modal = true;
        $this->empleado = $empleado;
        $this->titleModal = 'Asignar Beneficios';
        $this->subtitleModal = 'Empleado: ' . $empleado->EmpApellidoPaterno . ' ' . $empleado->EmpApellidoMaterno . ', ' . $empleado->EmpNombres;
        $this->buttonModal = 'Guardar';
        $this->accionModal = 'guardarBeneficios';

        $operacion = Operacion::query()
            ->where('EmpID', $empleado->EmpID)
            ->first();

        if ($operacion) {
            $this->beneficio = $operacion->OperacionBeneficios;
            $this->monto = $operacion->OperacionMontoBeneficios;
            $this->mesesSeleccionados = json_decode($operacion->OperacionMesesBeneficios);
        }
    }

    public function guardarBeneficios()
    {
        $this->validate([
            'beneficio' => 'required',
            'monto' => 'required|numeric',
            'mesesSeleccionados' => 'required|array',
        ]);

        $operacion = Operacion::query()
            ->where('EmpID', $this->empleado->EmpID)
            ->first();

        if (!$operacion) {
            $operacion = new Operacion();
            $operacion->EmpID = $this->empleado->EmpID;
            $operacion->save();
        }

        $operacion->OperacionBeneficios = $this->beneficio;
        $operacion->OperacionMontoBeneficios = $this->monto;
        $operacion->OperacionMesesBeneficios = json_encode($this->mesesSeleccionados);
        $operacion->save();

        // Cerrar Modal y Mostrar Mensaje de Exito
        $this->modal = false;
        $this->success(
            '¡Exito!',
            'Se asignaron los beneficios correctamente.'
        );

        // Limpiar Variables
        $this->reset();
    }

    public function asignarBono(Empleado $empleado)
    {
        $this->reset();

        $this->modal = true;
        $this->empleado = $empleado;
        $this->titleModal = 'Asignar Bono';
        $this->subtitleModal = 'Empleado: ' . $empleado->EmpApellidoPaterno . ' ' . $empleado->EmpApellidoMaterno . ', ' . $empleado->EmpNombres;
        $this->buttonModal = 'Guardar';
        $this->accionModal = 'guardarBono';

        $operacion = Operacion::query()
            ->where('EmpID', $empleado->EmpID)
            ->first();

        if ($operacion) {
            $this->montoBono = $operacion->OperacionBonoProductividad;
            $this->mesesBono = $operacion->OperacionMesAsignacionBono;
        }
    }

    public function guardarBono()
    {
        $this->validate([
            'montoBono' => 'required|numeric',
            'mesesBono' => 'required|numeric',
        ]);

        $operacion = Operacion::query()
            ->where('EmpID', $this->empleado->EmpID)
            ->first();

        if (!$operacion) {
            $operacion = new Operacion();
            $operacion->EmpID = $this->empleado->EmpID;
            $operacion->save();
        }

        $operacion->OperacionBonoProductividad = $this->montoBono;
        $operacion->OperacionMesAsignacionBono = $this->mesesBono;
        $operacion->save();

        // Cerrar Modal y Mostrar Mensaje de Exito
        $this->modal = false;
        $this->success(
            '¡Exito!',
            'Se asignó el bono correctamente.'
        );

        // Limpiar Variables
        $this->reset();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset();
    }

    public function verBoleta(Empleado $empleado)
    {
        $this->reset();

        $this->modal = true;
        $this->empleado = $empleado;
        $this->titleModal = 'Boleta de Pagos';
        $this->subtitleModal = 'Empleado: ' . $empleado->EmpApellidoPaterno . ' ' . $empleado->EmpApellidoMaterno . ', ' . $empleado->EmpNombres;
        $this->buttonModal = 'Abrir Boleta';
        $this->accionModal = 'abrirBoleta';
    }

    public function abrirBoleta()
    {
        $this->validate([
            'mesesBoleta' => 'required|numeric',
        ]);

        // Validamos si el mes seleccionado es mayor al mes actual
        if ($this->mesesBoleta > date('m')) {
            $this->error(
                '¡Error!',
                'No se puede generar la boleta para un mes futuro.'
            );
            return;
        }

        $this->modal = false;

        $this->dispatch('report:boleta',
            EmpID: $this->empleado->EmpID,
            MesID: $this->mesesBoleta
        );
    }

    public function headers(): array
    {
        return [
            ['key' => 'EmpID', 'label' => 'ID', 'class' => 'w-16'],
            ['key' => 'EmpCodigo', 'label' => 'Codigo'],
            ['key' => 'EmpNombres', 'label' => 'Nombres', 'class' => 'w-64'],
            ['key' => 'EmpCorreoElectronico', 'label' => 'Correo Electronico'],
            ['key' => 'GeneroID', 'label' => 'Genero'],
            ['key' => 'AreaID', 'label' => 'Area'],
            ['key' => 'EmpFechaNacimiento', 'label' => 'F. Nacimiento'],
            ['key' => 'acciones', 'label' => 'Acciones', 'class' => 'w-32'],
        ];
    }

    public function with(): array
    {
        $empleados = Empleado::query()
            ->whereAny(['EmpNombres'], 'LIKE', '%' . $this->search . '%')
            ->get();

        $beneficios = collect([
            ['BeneficioNombre' => 'Gratificación de Julio y Diciembre'],
            ['BeneficioNombre' => 'CTS'],
            ['BeneficioNombre' => 'Vacaciones'],
            ['BeneficioNombre' => 'Seguro de Vida'],
            ['BeneficioNombre' => 'Seguro de Salud']
        ]);

        $meses = collect([
            ['MesID' => 1, 'MesNombre' => 'Enero'],
            ['MesID' => 2, 'MesNombre' => 'Febrero'],
            ['MesID' => 3, 'MesNombre' => 'Marzo'],
            ['MesID' => 4, 'MesNombre' => 'Abril'],
            ['MesID' => 5, 'MesNombre' => 'Mayo'],
            ['MesID' => 6, 'MesNombre' => 'Junio'],
            ['MesID' => 7, 'MesNombre' => 'Julio'],
            ['MesID' => 8, 'MesNombre' => 'Agosto'],
            ['MesID' => 9, 'MesNombre' => 'Setiembre'],
            ['MesID' => 10, 'MesNombre' => 'Octubre'],
            ['MesID' => 11, 'MesNombre' => 'Noviembre'],
            ['MesID' => 12, 'MesNombre' => 'Diciembre'],
        ]);

        return [
            'empleados' => $empleados,
            'headers' => $this->headers(),
            'beneficios' => $beneficios,
            'meses' => $meses
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Gestion de Empleados" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Ingresar" responsive icon="o-plus" class="btn-primary" link="/create" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$empleados">
            @scope('cell_EmpID', $empleado)
            {{ $empleado->EmpID }}
            @endscope
            @scope('cell_EmpCodigo', $empleado)
            <div class="font-semibold">
                {{ $empleado->EmpCodigo }}
            </div>
            @endscope
            @scope('cell_EmpNombres', $empleado)
            <div>
                <div class="font-semibold">
                    {{ $empleado->EmpApellidoPaterno . ' ' . $empleado->EmpApellidoMaterno . ', ' . $empleado->EmpNombres }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $empleado->EmpDNI }}
                </div>
            </div>
            @endscope
            @scope('cell_EmpCorreoElectronico', $empleado)
            {{ $empleado->EmpCorreoElectronico }}
            @endscope
            @scope('cell_GeneroID', $empleado)
            {{ $empleado->genero->GeneroNombre }}
            @endscope
            @scope('cell_AreaID', $empleado)
            {{ $empleado->area->AreaNombre }}
            @endscope
            @scope('cell_EmpFechaNacimiento', $empleado)
            {{ $empleado->fechaNacimiento() }}
            @endscope
            @scope('cell_acciones', $empleado)
            <div class="flex space-x-2">
                <x-button
                    icon="o-calendar"
                    wire:click="calcularEdad({{ $empleado->EmpDNI }})"
                    class="text-blue-500 btn-sm"
                    tooltip="Calcular Edad"
                />
                <x-button
                    icon="o-question-mark-circle"
                    wire:click="calcularAntiguedadEmpresa({{ $empleado->EmpDNI }})"
                    class="text-sky-500 btn-sm"
                    tooltip="Calcular Antiguedad de Empresa"
                />
                <x-button
                    icon="o-pencil-square"
                    class="text-orange-500 btn-sm"
                    tooltip="Editar"
                    link="/edit/{{ $empleado->EmpID }}"
                />
                <x-dropdown class="btn-sm">
                    <x-menu-item
                        title="Asignar Beneficios"
                        icon="o-shield-check"
                        wire:click="asignarBeneficios({{ $empleado->EmpID }})"
                    />
                    <x-menu-item
                        title="Asignar Bono"
                        icon="o-banknotes"
                        wire:click="asignarBono({{ $empleado->EmpID }})"
                    />
                    <x-menu-item
                        title="Ver Boleta de Pagos"
                        icon="o-credit-card"
                        wire:click="verBoleta({{ $empleado->EmpID }})"
                    />
                </x-dropdown>
                {{-- <x-button icon="o-trash" spinner class="text-red-500 btn-sm" tooltip="Eliminar" wire:click="alertaDelete({{ $usuario->UsuId }})" /> --}}
            </div>
            @endscope
        </x-table>
    </x-card>

    <!-- ALERTA -->
    <x-modal wire:model="modalAlerta" title="{{ $titleModalAlerta }}" subtitle="{{ $subtitleModalAlerta }}">
        {{ $textModalAlerta }}
        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modalAlerta = false" />
            <x-button label="{{ $buttonModalAlerta }}" class="btn-success" wire:click="{{ $actionModalAlerta }}" />
        </x-slot:actions>
    </x-modal>

    <!-- MODAL -->
    <x-modal wire:model="modal" title="{{ $titleModal }}" subtitle="{{ $subtitleModal }}" separator>
        @if ($accionModal == 'guardarBeneficios')
            <x-form>
                <x-select
                    label="Beneficios"
                    :options="$beneficios"
                    option-value="BeneficioNombre"
                    option-label="BeneficioNombre"
                    placeholder="Seleccione los beneficios a asignar"
                    wire:model.live="beneficio"
                    inline
                />
                <x-input
                    label="Monto"
                    wire:model.live="monto"
                    type="number"
                    inline
                />
                <div class="text-sm text-gray-500">
                    Mes de Asignación:
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($meses as $item)
                        <x-checkbox
                            label="{{ $item['MesNombre'] }}"
                            wire:model.live="mesesSeleccionados"
                            value="{{ $item['MesID'] }}"
                            wire:key="mesesSeleccionados.{{ $item['MesID'] }}"
                        />
                    @endforeach
                </div>
            </x-form>
        @endif

        @if ($accionModal == 'guardarBono')
            <x-form>
                <x-input
                    label="Monto"
                    wire:model.live="montoBono"
                    type="number"
                    inline
                />
                <div class="text-sm text-gray-500">
                    Mes de Asignación:
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($meses as $item)
                        <label for="mesesBono.{{ $item['MesID'] }}" class="flex items-center">
                            <input
                                type="radio"
                                wire:model.live="mesesBono"
                                value="{{ $item['MesID'] }}"
                                id="mesesBono.{{ $item['MesID'] }}"
                                class="radio radio-primary @error('mesesBono') radio-error @enderror"
                            />
                            <span
                                class="ml-2 @error('mesesBono') text-red-500 @enderror"
                            >
                                {{ $item['MesNombre'] }}
                            </span>
                        </label>
                    @endforeach
                    @error('mesesBono')
                        <div class="col-span-3 text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </x-form>
        @endif

        @if ($accionModal == 'abrirBoleta')
            <x-form>
                <x-select
                    label="Mes de Boleta"
                    :options="$meses"
                    option-value="MesID"
                    option-label="MesNombre"
                    placeholder="Seleccione el mes de la boleta"
                    wire:model.live="mesesBoleta"
                    inline
                />
            </x-form>
        @endif

        <x-slot:actions>
            <x-button
                label="Cancel"
                wire:click="cerrarModal"
            />
            <x-button
                label="{{ $buttonModal }}"
                class="btn-primary"
                wire:click="{{ $accionModal }}"
            />
        </x-slot:actions>
    </x-modal>
</div>

@script
<script>
    $wire.on('report:boleta', (event) => {
        const a = document.createElement('a');
        a.href = '/report/boleta/' + event.EmpID + '/' + event.MesID;
        a.target = '_blank';
        a.click();
    });
</script>
@endscript
