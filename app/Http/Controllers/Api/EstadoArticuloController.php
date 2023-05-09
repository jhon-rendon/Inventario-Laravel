<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EstadoArticuloRequest;
use App\Models\EstadoArticulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EstadoArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estadoArticulo =  EstadoArticulo::all();
        return $estadoArticulo;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstadoArticuloRequest $request)
    {
        $data = $request->all();

        EstadoArticulo::create($data);

        return response()->json([
            "success" => true,
            "message" => "¡Registro del estado para el articulo exitosa!",
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

        try
         {
             $estadoArticulo = EstadoArticulo::findOrFail($id);
             return $estadoArticulo;
         }
         // catch(Exception $e) catch any exception
         catch(ModelNotFoundException $e)
         {
             return response()->json([
                 "success" => false,
                 "message" => "Estado del Articulo No encontrado",
             ],404);
         }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EstadoArticuloRequest $request, $id)
    {
        $input = $request->only(['estado',]);
        Helper::sendErrorUpdate( $id, $request, $input );

         try{
             $estadoArticulo = EstadoArticulo::findOrFail($id);

             if( $estadoArticulo->update($request->all()) ){

                 return response()->json([
                     "success" => true,
                     "message" => "Estado del Arciculo Actualizado",
                     "data" => $estadoArticulo
                 ]);

             }else{

                 return response()->json([
                     "success" => false,
                     "message" => "Error al actualizar el Estado del Articulo",
                 ],400);
             }
         }
         catch(ModelNotFoundException $e)
         {
             return response()->json([
                 "success" => false,
                 "message" => "Estado del Articulo No encontrado",
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
