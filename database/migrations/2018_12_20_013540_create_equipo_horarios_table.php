<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipoHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_horarios', function (Blueprint $table) {
            $table->integer('idHorario')->unsigned();            
            $table->integer('idEquipo')->unsigned();   
            $table->primary(['idHorario','idEquipo']);
            $table->foreign('idHorario')->references('id')->on('horarios');
            $table->foreign('idEquipo')->references('id')->on('equipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipo_horarios');
    }
}
