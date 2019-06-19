<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipo;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $equipos = Equipo::join('categorias','equipos.idcategoria','=','categorias.id')
            ->select('equipos.id','equipos.idcategoria','equipos.codigo','equipos.marca','categorias.nombre as nombre_categoria','equipos.modelo','equipos.estado')
            ->orderBy('estado', 'desc')->orderBy('equipos.id', 'desc')->paginate(7);
        }
        else{
            $equipos = Equipo::join('categorias','equipos.idcategoria','=','categorias.id')
            ->select('equipos.id','equipos.idcategoria','equipos.codigo','equipos.marca','categorias.nombre as nombre_categoria','equipos.modelo','equipos.estado')
            ->where('equipos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('estado', 'desc')->orderBy('equipos.id', 'desc')->paginate(7);

           // $equipos = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('estado', 'desc')->orderBy('id', 'desc')->paginate(7);
        }
               
        return [
            'pagination' => [
                'total'        => $equipos->total(),
                'current_page' => $equipos->currentPage(),
                'per_page'     => $equipos->perPage(),
                'last_page'    => $equipos->lastPage(),
                'from'         => $equipos->firstItem(),
                'to'           => $equipos->lastItem(),
            ],
            'equipos' => $equipos
        ];

    }

   public function listarEquipo(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $equipos=Equipo::where('estado','=','1')->orderBy('id','desc')->paginate(10);
        }
        else{
            $equipos = Equipo::where($criterio,'like','%'.$buscar.'%')->where('estado','=','1')->orderBy('id','desc')->paginate(10);
        }
     
        return ['equipos' => $equipos];
        
    }


    public function listarPdf(){
        $equipos = Equipo::join('categorias','equipos.idcategoria','=','categorias.id')
        ->select('equipos.id','equipos.idcategoria','equipos.codigo','equipos.marca','categorias.nombre as nombre_categoria','equipos.modelo','equipos.estado')
        ->orderBy('estado', 'desc')->orderBy('equipos.id', 'desc')->get();

        $cont=Equipo::count();

        $pdf = \PDF::loadView('pdf.equipospdf',['equipos'=>$equipos, 'cont'=>$cont]);
        return $pdf->stream('equipos.pdf');

     }

    
    public function selectEquipo(Request $request)
    {
       // if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $equipos = Equipo::join('categorias','categorias.id','=','equipos.idcategoria')
        ->where('equipos.estado','=','1')
        ->where('codigo','like','%'.$filtro.'%')
        ->select('equipos.id','codigo','marca','modelo','categorias.nombre as categoria')
        ->get();
        return ['equipos'=>$equipos];
        
    }
    public function listarEquipoReserva(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $equipos = Equipo::join('categorias','equipos.idcategoria','=','categorias.id')
            ->select('equipos.id','equipos.codigo as codigo','categorias.nombre as categoria','equipos.estado')
            
            ->orderBy('estado', 'desc')->orderBy('equipos.id', 'desc')->paginate(10);

        }
        else{
            $equipos = Equipo::join('categorias','equipos.idcategoria','=','categorias.id')
            ->select('equipos.id','equipos.codigo as codigo','categorias.nombre as categoria','equipos.estado')
            ->where('categoria.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('estado', 'desc')->orderBy('equipos.id', 'desc')->paginate(10);

           // $equipos = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('estado', 'desc')->orderBy('id', 'desc')->paginate(7);
        }
        return ['equipos' => $equipos];
        
    }
    
        
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $equipo = new Equipo();
        $equipo->idcategoria = $request->idcategoria;
        $equipo->codigo = $request->codigo;
        $equipo->marca = $request->marca;
        $equipo->modelo = $request->modelo;
        $equipo->estado = '1';
        $equipo->save();
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
        $equipo = Equipo::findOrFail($request->id);
        $equipo->estado = '0';
        $equipo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $equipo = Equipo::findOrFail($request->id);       
        $equipo->estado = '1';
        $equipo->save();
    }

}
