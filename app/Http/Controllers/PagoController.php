<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Pago;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $pagos = Pago::join('clientes','pagos.idCliente','=','clientes.id')
            ->select('pagos.id','pagos.idCliente','pagos.monto','pagos.fecha','clientes.nombre','pagos.estado')
            ->orderBy('estado', 'desc')->orderBy('pagos.id', 'desc')->paginate(7);
        }
        else{
            $pagos = Pago::join('clientes','pagos.idCliente','=','clientes.id')
            ->select('pagos.id','pagos.idCliente','pagos.monto','pagos.fecha','clientes.nombre as nombre_cliente','pagos.estado')
            ->where('pagos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('estado', 'desc')->orderBy('pagos.id', 'desc')->paginate(7);

        
        }
               
        return [
            'pagination' => [
                'total'        => $pagos->total(),
                'current_page' => $pagos->currentPage(),
                'per_page'     => $pagos->perPage(),
                'last_page'    => $pagos->lastPage(),
                'from'         => $pagos->firstItem(),
                'to'           => $pagos->lastItem(),
            ],
            'pagos' => $pagos
        ];

    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $mytime= Carbon::now('America/La_Paz');

        $pago = new Pago();
        $pago->idCliente = $request->idCliente;
        $pago->monto = $request->monto;
        $pago->fecha= $mytime->toDateTimeString();     
        $pago->estado = '1';
        $pago->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $mytime= Carbon::now('America/La_Paz');
        $pago = Pago::findOrFail($request->id);
        $pago->idCliente = $request->idCliente;
        $pago->monto = $request->monto;
        $pago->fecha= $mytime->toDateTimeString();     
        $pago->estado = '1';
        $pago->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pago = Pago::findOrFail($request->id);
        $pago->estado = '0';
        $pago->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pago = Pago::findOrFail($request->id);
        $pago->estado = '1';
        $pago->save();
    }

}




