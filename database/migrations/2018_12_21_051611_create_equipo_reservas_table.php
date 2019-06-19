<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipoReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_reservas', function (Blueprint $table) {
            $table->integer('idReserva')->unsigned();     
            $table->integer('idHorario')->unsigned();         
            $table->integer('idEquipo')->unsigned();                     
            $table->time('horaInicio');
            $table->time('horaFin');
            $table->decimal('precio', 11,2);
            $table->boolean('estado')->default(1);
            $table->primary(['idReserva','idHorario','idEquipo']);
            $table->foreign('idReserva')->references('id')->on('reservas');
            $table->foreign('idHorario')->references('idHorario')->on('equipo_horarios');
            $table->foreign('idEquipo')->references('idEquipo')->on('equipo_horarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipo_reservas');
    }
}
