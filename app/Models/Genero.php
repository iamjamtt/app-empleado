<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'Genero';
    protected $primaryKey = 'GeneroID';
    public $timestamps = false;
    protected $fillable = [
        'GeneroID',
        'GeneroNombre',
    ];
}
