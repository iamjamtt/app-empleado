<?php

use App\Http\Controllers\ReportesController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'empleados.index')->name('empleados.index');
Volt::route('/create', 'empleados.formulario')->name('empleados.formulario.create');
Volt::route('/edit/{EmpID}', 'empleados.formulario')->name('empleados.formulario.edit');

Route::get('/report/boleta/{EmpID}/{MesID}', [ReportesController::class, 'boletaPagos'])->name('reportes.boletaPagos');

//
