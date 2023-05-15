<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Requests\TipoUbicacionRequest;
use App\Models\TipoUbicacion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoUbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

    }

    public function index(Request $request )
    {
        if( !$request->query('paginate') || $request->query('paginate') !== 'false' ){
            $tipoUbicacion = TipoUbicacion::orderBy('tipo', 'asc')->paginate(10);
        }
        else{
            $tipoUbicacion = TipoUbicacion::orderBy('tipo', 'asc')->get(["id","tipo"]);
        }

        return $tipoUbicacion;
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
    public function store(TipoUbicacionRequest $request)
    {

        $data = $request->all();
        TipoUbicacion::create($data);

        return response()->json([
            "success" => true,
            "message" => "¡Registro del tipo de ubicación exitoso!",
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
        Helper::sendErrorShow($id);
        try{
            return response()->json([
                "success" => true,
                "message" => "Listado de tipo ubicacion",
                "data"    => TipoUbicacion::findOrFail($id)
            ],200);
        }catch(ModelNotFoundException $e){

            return response()->json([
                "success" => false,
                "data"    => null,
                "message" => "No existe tipo de ubicacion con el ID ".$id
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoUbicacionRequest $request, $id)
    {
        $input = $request->only(['tipo']);
        Helper::sendErrorUpdate( $id, $request, $input );

        $text = "Tipo de Ubicación";
        try{
            $marca = TipoUbicacion::findOrFail($id);

            if( $marca->update( $input ) ){

                return response()->json([
                    "success" => true,
                    "message" => $text." Actualizada",
                    "data"    => $marca
                ]);

            }else{

                return response()->json([
                    "success" => false,
                    "message" => "Error al actualizar el ".$text,
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => $text." No encontrada",
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
