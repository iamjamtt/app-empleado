<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operacion extends Model
{
    use HasFactory;

    protected $table = 'Operacion';
    protected $primaryKey = 'OperacionID';
    public $timestamps = false;
    protected $fillable = [
        'OperacionID',
        'EmpID',
        'OperacionBeneficios',
        'OperacionMontoBeneficios',
        'OperacionMesesBeneficios',
        'OperacionBonoProductividad',
        'OperacionMesAsignacionBono'
    ];

    protected $casts = [
        'OperacionMesesBeneficios' => 'array',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'EmpID', 'EmpID');
    }
}
