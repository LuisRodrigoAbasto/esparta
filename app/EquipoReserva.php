<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipoReserva extends Model
{
  //  protected $table = 'equipo_reservas';
    protected $fillable = ['idReserva','idHorario','idEquipo','horaInicio','horaFin','precio'];

    public $timestamps = false;


}
