<?php

namespace App\Http\Controllers\optgen;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\NoOrphanRegisters;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade;
use Barryvdh\DomPDF\PDF;
use Log;
use Validator;

use App\modelos\Generales;

class OptgenCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('optbasic/opcionesgenerales');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $obj=Generales::all();
        return $obj->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id=null)
    {
        $mensaje='No se ha guardado nada.';
        $validacion=Validator::make($request->all(),[
            'nombre' => 'required',
            'valor'=>'required',
            'descripcion'=>'required'
        ]);
        if(!$validacion->fails()){
            $obj=new Generales;
            if (!is_null($id)) {
                $obj=Generales::findOrFail($id);
            }
            $obj->nombre=$request->input('nombre');
            $obj->valor=$request->input('valor');
            $obj->descripcion=$request->input('descripcion');
            $obj->save();
            $mensaje='Se ha guardado el registro con id= '.$obj->id;
        }
        return response()->json(['mensaje'=>$mensaje]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $obj=Generales::findOrFail($id);
        return $obj->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Sin uso
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->store($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj=Generales::findOrFail($id);
        $obj->delete();
        return response()->json(['mensaje'=>'registro '.$id.' borrado exitosamente']);
    }
}
