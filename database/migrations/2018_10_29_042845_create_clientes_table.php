<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->string('apellido',120)->nullable();
            $table->string('ci',30)->nullable();
            $table->dateTime('fechaNacimiento');
            $table->string('direccion',150)->nullable();
            $table->string('telefono',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('clave',50)->nullable();
            $table->boolean('estado')->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
