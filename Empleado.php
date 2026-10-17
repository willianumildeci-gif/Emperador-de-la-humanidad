<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';

    protected $primaryKey = 'IdEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'Dni',
        'Nombres',
        'Telefono',
        'Estado',
        'User',
    ];
}
