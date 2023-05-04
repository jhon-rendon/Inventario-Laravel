<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarcaRequest;
use App\Models\Marca;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = Marca::all();
        $count  = $marcas->count();

        if( $count > 0 ){
            return response()->json([
                "msg"=>"Listado de Marcas",
                "status"=>true,
                "count" => $count,
                "data"  => $marcas
            ],200);
        }else{
            return response()->json([
                "msg"=>"No existen registros de Marcas",
                "status"=>false,
            ],404);
        }
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
    public function store(MarcaRequest $request)
    {
        $data     = $request->all();
        $registro = Marca::create($data);

        return response()->json([
            "status" => 1,
            "msg"    => "¡Registro de marca exitoso!",
            "data"   => $registro
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return response()->json([
                "status" => true,
                "data"   => Marca::findOrFail($id)
            ],200);
        }catch(ModelNotFoundException $e){

            return response()->json([
                "status" => false,
                "data"   => null,
                "msg"    => "No existe marca con el ID ".$id
            ],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MarcaRequest $request, $id)
    {
        if ( count( $request->all() ) === 0 ){
            return response()->json([
                "status" => false,
                "msg" => " No se ha enviado ningun parametro valido",
                ],404);
        }
        try{
            $marca = Marca::findOrFail($id);

            if( $marca->update($request->all()) ){

                return response()->json([
                    "status" => true,
                    "msg" => "Marca Actualizada",
                    "articulo" => $marca
                ]);

            }else{

                return response()->json([
                    "status" => false,
                    "msg" => "Error al actualizar la Marca",
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "status" => false,
                "msg" => "Marca No encontrada",
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
