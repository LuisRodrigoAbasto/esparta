<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('contenido/contenido');
});

Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store');
Route::put('/categoria/actualizar', 'CategoriaController@update');
Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
Route::put('/categoria/activar', 'CategoriaController@activar');
Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');


Route::get('/equipo', 'EquipoController@index');
Route::post('/equipo/registrar', 'EquipoController@store');
Route::put('/equipo/actualizar', 'EquipoController@update');
Route::put('/equipo/desactivar', 'EquipoController@desactivar');
Route::put('/equipo/activar', 'EquipoController@activar');
Route::get('/equipo/listarEquipoReserva', 'EquipoController@listarEquipoReserva');
Route::get('/equipo/listarEquipo', 'EquipoController@listarEquipo');
Route::get('/equipo/selectEquipo', 'EquipoController@selectEquipo');
Route::get('/equipo/listarPdf', 'EquipoController@listarPdf')->name('equipos_pdf');

Route::get('/cliente', 'ClienteController@index');
Route::post('/cliente/registrar', 'ClienteController@store');
Route::put('/cliente/actualizar', 'ClienteController@update');
Route::put('/cliente/desactivar', 'ClienteController@desactivar');
Route::put('/cliente/activar', 'ClienteController@activar');
Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');


Route::get('/pago', 'PagoController@index');
Route::post('/pago/registrar', 'PagoController@store');
Route::put('/pago/actualizar', 'PagoController@update');
Route::put('/pago/desactivar', 'PagoController@desactivar');
Route::put('/pago/activar', 'PagoController@activar');


Route::get('/empleado','EmpleadoController@index');
Route::post('/empleado/registrar','EmpleadoController@store');
Route::put('/empleado/actualizar','EmpleadoController@update');
Route::put('/empleado/desactivar','EmpleadoController@desactivar');
Route::put('/empleado/activar','EmpleadoController@activar');
Route::get('/empleado/selectEmpleado', 'EmpleadoController@selectEmpleado');

Route::get('/reserva','ReservaController@index');
Route::post('/reserva/registrar','ReservaController@store');
Route::put('/reserva/desactivar','ReservaController@desactivar');

Route::get('/horario','HorarioController@index');
Route::post('/horario/registrar','HorarioController@store');
Route::put('/horario/actualizar','HorarioController@update');
Route::put('/horario/desactivar','HorarioController@desactivar');
Route::put('/horario/activar','HorarioController@activar');