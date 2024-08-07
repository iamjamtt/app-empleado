<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalidadContrato extends Model
{
    use HasFactory;

    protected $table = 'ModalidadContrato';
    protected $primaryKey = 'ModalidadID';
    public $timestamps = false;
    protected $fillable = [
        'ModalidadID',
        'ModalidadNombre',
    ];
}
