<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JornadaLaboral extends Model
{
    use HasFactory;

    protected $table = 'JornadaLaboral';
    protected $primaryKey = 'JornadaID';
    public $timestamps = false;
    protected $fillable = [
        'JornadaID',
        'JornadaNombre',
    ];
}
