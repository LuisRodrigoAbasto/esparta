<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre','apellido','ci','fechaNacimiento','direccion','telefono','email','clave','estado'];

    public function pago()
    {
        return $this->hasMany('App\Pago');
    }
   
}
