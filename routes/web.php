<?php

use Livewire\Volt\Volt;

Volt::route('/', 'empleados.index')->name('empleados.index');
Volt::route('/create', 'empleados.formulario')->name('empleados.formulario.create');
Volt::route('/edit/{EmpID}', 'empleados.formulario')->name('empleados.formulario.edit');
