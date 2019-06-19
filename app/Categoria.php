<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
  //  protected $table = 'categorias';
  //protected $primaryKey = 'id';
  protected $fillable = ['nombre','descripcion','estado'];

  public function equipo()
    {
        return $this->hasMany('App\Equipo');
    }
   

}
