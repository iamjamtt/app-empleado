<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function boletaPagos($EmpID, $MesID)
    {
        // verificamos si el empleado existe
        $empleado = Empleado::find($EmpID);

        if (!$empleado) {
            abort(404, 'Empleado no encontrado');
        }

        // verificamos si el mes existe
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        if (!isset($meses[$MesID])) {
            abort(404, 'Mes no encontrado');
        }

        // validar si el mes es mayor al mes actual
        $mesActual = date('n');

        if ($MesID > $mesActual) {
            abort(404, 'Mes no permitido');
        }

        // aquí va la lógica para generar la boleta de pagos
        // obtenemos el mes en texto
        $mes = $meses[$MesID];

        // obtenemos su salario
        $salario = $empleado->area->AreaSalarioBase;

        // verificamos si tiene operaciones

        $operacion = $empleado->operacion;

        // variables para los beneficios
        $beneficio = "";
        $salarioBeneficio = 0;
        $tieneBeneficio = false;

        // variable para bono
        $tieneBono = false;
        $montoBono = 0;
        $mesesBono = "";

        // verificamos si tiene operaciones
        if ($operacion) {
            // verificamos si tiene beneficios
            $beneficio = $operacion->OperacionBeneficios;
            if ($beneficio) {
                // verificamos si el beneficio corresponde al mes ingresado
                $mesesBeneficio = json_decode($operacion->OperacionMesesBeneficios);
                $tieneBeneficio = in_array($MesID, $mesesBeneficio);
                if ($tieneBeneficio) {
                    $salarioBeneficio = $operacion->OperacionMontoBeneficios;
                }
            }

            // verificamos si tiene bono de productividad
            if ($operacion->OperacionBonoProductividad > 0) {
                $tieneBono = true;
                $montoBono = $operacion->OperacionBonoProductividad;
                $mesesBono = $meses[$MesID];
            }
        }

        // calculamos el total a pagar
        $total = $salario + $salarioBeneficio + $montoBono;

        // mandamos los datos al pdf de pagos
        $data = [
            'empleado' => $empleado,
            'mes' => $mes,
            'salario' => $salario,
            'beneficio' => $beneficio,
            'salarioBeneficio' => $salarioBeneficio,
            'tieneBeneficio' => $tieneBeneficio,
            'tieneBono' => $tieneBono,
            'montoBono' => $montoBono,
            'mesesBono' => $mesesBono,
            'total' => $total,
        ];
        $pdf = Pdf::loadView('components.reports.boleta', $data);
        return $pdf->stream('boleta.pdf');
    }
}
