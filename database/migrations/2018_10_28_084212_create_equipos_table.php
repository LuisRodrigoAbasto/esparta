<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria')->unsigned();
            $table->string('codigo',50)->nullable();
            $table->string('marca',100)->nullable();
            $table->string('modelo',50)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('idcategoria')->references('id')->on('categorias');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipos');
    }
}
