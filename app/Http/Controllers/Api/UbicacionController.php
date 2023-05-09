<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Requests\UbicacionRequest;
use App\Models\Ubicacion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;


class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ubicacion =  Ubicacion::all();
        return $ubicacion;
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
    public function store(UbicacionRequest $request)
    {
        $data = $request->all();
        Ubicacion::create($data);

        return response()->json([
            "success" => true,
            "message" => "¡Registro ubicación exitoso!",
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
                "message" => "Listado de ubicacion",
                "data"    => Ubicacion::findOrFail($id)
            ],200);
        }catch(ModelNotFoundException $e){

            return response()->json([
                "success" => false,
                "data"    => null,
                "message" => "No existe la ubicacion con el ID ".$id
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
    public function update(UbicacionRequest $request, $id)
    {

        $input = $request->only(['nombre','codigo','direccion','tipo_ubicacion_id']);
        Helper::sendErrorUpdate( $id, $request, $input );

        $text = "Ubicación";
        try{
            $marca = Ubicacion::findOrFail($id);

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
