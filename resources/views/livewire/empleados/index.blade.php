<?php

use App\Models\Empleado;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Title;

new
#[Title('Gestion de Empleados')]
class extends Component {
    use Toast;

    public string $search = '';

    // Modal Alerta
    public bool $modalAlerta = false;
    public string $titleModalAlerta = '';
    public string $subtitleModalAlerta = '';
    public string $textModalAlerta = '';
    public string $buttonModalAlerta = '';
    public string $actionModalAlerta = '';

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

        return [
            'empleados' => $empleados,
            'headers' => $this->headers()
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
                {{-- <x-button icon="o-trash" spinner class="text-red-500 btn-sm" tooltip="Eliminar" wire:click="alertaDelete({{ $usuario->UsuId }})" /> --}}
            </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modalAlerta" title="{{ $titleModalAlerta }}" subtitle="{{ $subtitleModalAlerta }}">
        {{ $textModalAlerta }}
        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modalAlerta = false" />
            <x-button label="{{ $buttonModalAlerta }}" class="btn-success" wire:click="{{ $actionModalAlerta }}" />
        </x-slot:actions>
    </x-modal>
</div>
