<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idPago')->unsigned();
            $table->foreign('idPago')->references('id')->on('pagos');
            $table->integer('idEmpleado')->unsigned();
            $table->foreign('idEmpleado')->references('id')->on('empleados');
            $table->date('fecha');
            $table->decimal('montoTotal', 11,2);
            $table->boolean('estado')->default(1);
         //   $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
