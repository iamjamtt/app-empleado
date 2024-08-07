<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'Empleado';
    protected $primaryKey = 'EmpID';
    public $timestamps = false;
    protected $fillable = [
        'EmpID',
        'EmpCodigo',
        'EmpDNI',
        'EmpNombres',
        'EmpApellidoPaterno',
        'EmpApellidoMaterno',
        'GeneroID',
        'AreaID',
        'ModalidadID',
        'JornadaID',
        'EmpFechaInicio',
        'EmpFechaNacimiento',
        'EmpFechaIngreso',
        'EmpCorreoElectronico',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'AreaID', 'AreaID');
    }

    public function genero(): BelongsTo
    {
        return $this->belongsTo(Genero::class, 'GeneroID', 'GeneroID');
    }

    public function jornada(): BelongsTo
    {
        return $this->belongsTo(JornadaLaboral::class, 'JornadaID', 'JornadaID');
    }

    public function modalidad(): BelongsTo
    {
        return $this->belongsTo(ModalidadContrato::class, 'ModalidadID', 'ModalidadID');
    }

    public function operacion(): HasOne
    {
        return $this->hasOne(Operacion::class, 'EmpID', 'EmpID');
    }

    public function fechaNacimiento(): string
    {
        $value = $this->EmpFechaNacimiento;
        // cambiar a formato: 21 Dic 1990
        $meses = [
            'Jan' => 'Ene',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'May' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Sep',
            'Oct' => 'Oct',
            'Nov' => 'Nov',
            'Dec' => 'Dic',
        ];
        $fecha = date('d', strtotime($value)) . ' ' . $meses[date('M', strtotime($value))] . ' ' . date('Y', strtotime($value));
        return $fecha;
    }
}
