<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['idCliente','monto','fecha','estado'];

    public function cliente(){
        return $this->belongsTo('App\Cliente');
     }
    
}
