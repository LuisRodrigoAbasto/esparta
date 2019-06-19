<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
   // protected $table = 'equipos';
    protected $fillable = ['idcategoria','codigo','marca','modelo','estado'];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
}
