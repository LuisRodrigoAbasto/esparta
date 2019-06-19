<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipoHorario extends Model
{
    protected $table = 'equipo_horarios';
    protected $fillable = ['idHorario','idEquipo'];
    public $timestamps = false;
}
