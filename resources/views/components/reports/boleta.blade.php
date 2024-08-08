<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Pagos</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .boleta {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        header h1 {
            margin: 0;
        }

        header .empresa {
            text-align: right;
        }

        header .fecha {
            text-align: right;
        }

        .informacion-empleado, .detalles-pagos {
            margin: 20px 0;
        }

        h3 {
            margin-bottom: 10px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f0f0f0;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="boleta">
        <header>
            <h1>
                Boleta de Pagos del Mes de {{ $mes }}
            </h1>
            <div class="empresa">
                <h2>SISEMP</h2>
                <p>Jr. Tupac 123</p>
                <p>Teléfono: (123) 456-7890</p>
            </div>
            <div class="fecha">
                <p>
                    Fecha de Emisión: {{ date('d/m/Y') }}
                </p>
            </div>
        </header>
        <section class="informacion-empleado">
            <h3>Información del Empleado</h3>
            <p>
                Nombre: {{ $empleado->EmpNombres }} {{ $empleado->EmpApellidoPaterno }} {{ $empleado->EmpApellidoMaterno }}
            </p>
            <p>Identificación: {{ $empleado->EmpDNI }}</p>
            <p>Área: {{ $empleado->area->AreaNombre }}</p>
            <p>Modalidad Contrado: {{ $empleado->modalidad->ModalidadNombre }}</p>
            <p>Jornada Laboral: {{ $empleado->jornada->JornadaNombre }}</p>
        </section>
        <section class="detalles-pagos">
            <h3>Detalles de Pagos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Salario Base
                        </td>
                        <td>
                            S/. {{ number_format($salario, 2, '.', '') }}
                        </td>
                    </tr>
                    @if ($tieneBeneficio)
                        <tr>
                            <td>
                                Beneficio: {{ $beneficio }}
                            </td>
                            <td>
                                S/. {{ number_format($salarioBeneficio, 2, '.', '') }}
                            </td>
                        </tr>
                    @endif
                    @if ($tieneBono)
                        <tr>
                            <td>
                                Bono del mes de {{ $mesesBono }}
                            </td>
                            <td>
                                S/. {{ number_format($montoBono, 2, '.', '') }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Total Neto</strong></td>
                        <td>
                            <strong>S/. {{ number_format($total, 2, '.', '') }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <footer>
            <p>Esta boleta de pagos es un documento generado electrónicamente y no requiere firma.</p>
        </footer>
    </div>
</body>
</html>
