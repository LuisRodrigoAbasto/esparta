<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    if (!$request->ajax()) return redirect('/');
        $buscar=$request->buscar;
        $criterio=$request->criterio;

        if($buscar=='')
        {
            $empleados = Empleado::orderBy('id','desc')->paginate(3);
        }
        else{
            $empleados = Empleado::where($criterio,'like','%'.$buscar.'%')->orderBy('id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total'        => $empleados->total(),
                'current_page' => $empleados->currentPage(),
                'per_page'     => $empleados->perPage(),
                'last_page'    => $empleados->lastPage(),
                'from'         => $empleados->firstItem(),
                'to'           => $empleados->lastItem(),
            ],
            'empleados' => $empleados
        ];
    }
    public function selectEmpleado(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $empleados = Empleado::where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->orwhere('apellido','like','%'.$filtro.'%')
        ->select('id',DB::raw("concat(nombre,' ',apellido) as empleado"))        
        ->orderBy('nombre','asc')
        ->get();
        return ['empleados'=>$empleados];
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   if (!$request->ajax()) return redirect('/');
        $empleado= new Empleado();
        $empleado->nombre=$request->nombre;
        $empleado->apellido=$request->apellido;
        $empleado->telefono=$request->telefono;
        $empleado->fechaNacimiento=$request->fechaNacimiento;
        $empleado->ci=$request->ci;
        $empleado->sueldo=$request->sueldo;
        $empleado->tipo=$request->tipo;
        $empleado->estado='1';
        $empleado->save();

    }


    public function update(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');
        $empleado= Empleado::findOrFail($request->id);
        $empleado->nombre=$request->nombre;
        $empleado->apellido=$request->apellido;
        $empleado->telefono=$request->telefono;
        $empleado->fechaNacimiento=$request->fechaNacimiento;
        $empleado->ci=$request->ci;
        $empleado->sueldo=$request->sueldo;
        $empleado->tipo=$request->tipo;
        $empleado->estado='1';
        $empleado->save();

    }

    public function desactivar(Request $request)
    {  if (!$request->ajax()) return redirect('/');
        $empleado= Empleado::findOrFail($request->id);
        $empleado->estado='0';
        $empleado->save();

    }

    public function activar(Request $request)
    {   if (!$request->ajax()) return redirect('/');
        $empleado= Empleado::findOrFail($request->id);
        $empleado->estado='1';
        $empleado->save();
    }


}
