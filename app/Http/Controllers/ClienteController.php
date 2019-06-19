<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cliente;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{


    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $clientes = Cliente::orderBy('estado', 'desc')->orderBy('id', 'desc')->paginate(7);
        }
        else{
            $clientes = Cliente::where($criterio, 'like', '%'. $buscar . '%')->orderBy('estado', 'desc')->orderBy('id', 'desc')->paginate(7);
        }
               
        return [
            'pagination' => [
                'total'        => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'per_page'     => $clientes->perPage(),
                'last_page'    => $clientes->lastPage(),
                'from'         => $clientes->firstItem(),
                'to'           => $clientes->lastItem(),
            ],
            'clientes' => $clientes
        ];

    }

    public function selectCliente(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $clientes = Cliente::where('estado','=','1')
        ->where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->orwhere('apellido','like','%'.$filtro.'%')
        ->select('id',DB::raw("concat(nombre,' ',apellido) as cliente"))        
        ->orderBy('nombre','asc')
        ->get();
        return ['clientes'=>$clientes];
        
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $mytime= Carbon::now('America/La_Paz');

        $cliente = new Cliente();
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->ci = $request->ci;
        $cliente->fechaNacimiento = $mytime->toDateTimeString();
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->clave = $request->clave;
        $cliente->estado = '1';
        $cliente->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $mytime= Carbon::now('America/La_Paz');

        $cliente = Cliente::findOrFail($request->id);
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->ci = $request->ci;
        $cliente->fechaNacimiento = $mytime->toDateTimeString();
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->clave = $request->clave;
        $cliente->estado = '1';
        $cliente->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = '0';
        $cliente->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = '1';
        $cliente->save();
    }



}
