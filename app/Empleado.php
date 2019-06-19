<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = ['nombre','apellido','telefono','fechaNacimiento','ci','sueldo','tipo','estado'];
}
