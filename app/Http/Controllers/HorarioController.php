<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Horario;
use App\EquipoHorario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $horarios = Horario::join('empleados','horarios.idEmpleado','=','empleados.id')
            ->select('horarios.id','horarios.idEmpleado','horarios.fechaInicio','horarios.fechaFin','horarios.estado',DB::raw("concat(nombre,' ',apellido) as empleado"))
            ->orderBy('estado', 'desc')->orderBy('horarios.id', 'desc')->paginate(7);
        }
        else{
            $horarios = Horario::join('empleados','horarios.idEmpleado','=','empleados.id')
            ->select('horarios.id','horarios.idEmpleado','horarios.fechaInicio','horarios.fechaFin','horarios.estado',DB::raw("concat(nombre,' ',apellido) as empleado"))
            ->where('empleados.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('empleados.apellido', 'like', '%'. $buscar . '%')
            ->orderBy('estado', 'desc')->orderBy('horarios.id', 'desc')->paginate(7);

           // $equipos = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('estado', 'desc')->orderBy('id', 'desc')->paginate(7);
        }
               
        return [
            'pagination' => [
                'total'        => $horarios->total(),
                'current_page' => $horarios->currentPage(),
                'per_page'     => $horarios->perPage(),
                'last_page'    => $horarios->lastPage(),
                'from'         => $horarios->firstItem(),
                'to'           => $horarios->lastItem(),
            ],
            'horarios' => $horarios
        ];

    }

    public function store(Request $request)
    {
     if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $horario = new Horario();
            $horario->idEmpleado = $request->idEmpleado;
            $mytime = Carbon::now('America/La_Paz');
            $horario->fechaInicio = '2018-12-12';
            $horario->fechaFin =  '2018-12-12';
            $horario->estado = '1';
            $horario->save();

            $detalles=$request->data;
            foreach($detalles as $ep=>$det)
            {
            $detalle = new EquipoHorario();
            $detalle->idHorario=$horario->id;
            $detalle->idEquipo=$det['idEquipo'];
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

        $mytime= Carbon::now('America/La_Paz');

        $horario = Horario::findOrFail($request->id);
        $horario->idEmpleado = $request->idEmpleado;
        $horario->fechaInicio = $mytime->toDateTimeString();
        $horario->fechaFin = $mytime->toDateTimeString();
        $horario->estado = '1';
        $horario->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $horario = Horario::findOrFail($request->id);
        $horario->estado = '0';
        $horario->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $horario = Horario::findOrFail($request->id);
        $horario->estado = '1';
        $horario->save();
    }
}
