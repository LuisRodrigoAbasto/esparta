<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Reserva;
use App\Pago;
use App\EquipoReserva;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $reservas = Reserva::join('pagos','reservas.idPago','=','pagos.id')
            ->join('empleados','reservas.idEmpleado','=','empleados.id')
           // ->select('reservas.id','reservas.idPago','reservas.idEmpleado','reservas.fecha','reservas.montoTotal','reservas.estado',
           // 'empleados.nombre','pagos.monto')
            ->orderBy('reservas.estado', 'desc')->orderBy('reservas.id', 'desc')->paginate(7);
        }
        else{
            $reservas = Reserva::join('pagos','reservas.idpago','=','pagos.id')
            ->join('empleados','reservas.idempleado','=','empleados.id')
          //  ->select('reservas.id','reservas.idpago','reservas.idempleado','reservas.fecha','reservas.montoTotal','reservas.estado',
          //  'empleados.nombre','pagos.monto')
            ->where('reservas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('reservas.estado', 'desc')->orderBy('reservas.id', 'desc')->paginate(7);

           
        }
               
        return [
            'pagination' => [
                'total'        => $reservas->total(),
                'current_page' => $reservas->currentPage(),
                'per_page'     => $reservas->perPage(),
                'last_page'    => $reservas->lastPage(),
                'from'         => $reservas->firstItem(),
                'to'           => $reservas->lastItem(),
            ],
            'reservas' => $reservas
        ];

    }
    public function store(Request $request)
    {
       

    if (!$request->ajax()) return redirect('/');

      try{
        DB::beginTransaction();
        $mytime= Carbon::now('America/La_Paz');

        $pago = new Pago();
        $pago->idCliente = $request->idCliente;
        $pago->monto = $request->montoTotal;
        $pago->fecha = $request->fecha;
        $pago->estado = '1';
        $pago->save();

        $reserva = new Reserva();
        $reserva->idPago = $pago->id;
        $reserva->idEmpleado = "1";
        $reserva->fecha = $request->fecha;
        $reserva->montoTotal = $request->montoTotal;
        $reserva->estado = '1';
        $reserva->save();

        $detalles=$request->data; //array equipo_reserva
        //recorre todos los elementos

        foreach($detalles as $er=>$det)
        {
           $detalle = new EquipoReserva();
           $detalle->idReserva=$reserva->id;
           $detalle->idHorario='1';
           $detalle->idEquipo='1';
           $detalle->horaInicio=$det['horaInicio'];
           $detalle->horaFin=$det['horaFin'];
           $detalle->precio=$det['precio'];
           $detalle->save();
        }
        
        DB::commit();
      }
      catch(Exeption $e){
          DB::rollBack();
      }
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $equipo = Equipo::findOrFail($request->id);
        $equipo->idcategoria = $request->idcategoria;
        $equipo->codigo = $request->codigo;
        $equipo->marca = $request->marca;
        $equipo->modelo = $request->modelo;
        $equipo->estado = '1';
        $equipo->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $reserva = Reserva::findOrFail($request->id);
        $reserva->estado = '0';
        $reserva->save();
    }
}
