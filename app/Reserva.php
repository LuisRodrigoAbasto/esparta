<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['idPago','idEmpleado','fecha','montoTotal','estado'];
    public $timestamps = false;
    public function pago(){
        return $this->belongsTo('App\Pago');
        }
    public function empleado(){
        return $this->belongsTo('App\Empleado');
    }

}