<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'empleado';

    protected $primaryKey = 'IdEmpleado';

    public $timestamps = false;

    protected $hidden = ['Dni'];

    public function getAuthPasswordName(): string
    {
        return 'Dni';
    }
}
