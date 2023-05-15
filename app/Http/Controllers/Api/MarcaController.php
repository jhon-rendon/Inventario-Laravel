<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use APP\Helpers\Helper as Helper;
use App\Http\Requests\MarcaRequest;
use App\Models\Marca;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = Marca::paginate(10);
        $count  = $marcas->count();

        if( $count > 0 ){
            return response()->json([
                "message" =>"Listado de Marcas",
                "success" => true,
                "count"   => $count,
                "data"    => $marcas
            ],200);
        }else{
            return response()->json([
                "message"  =>  "No existen registros de Marcas",
                "success"  =>  false,
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
            "success" => 1,
            "message" => "¡Registro de marca exitoso!",
            "data"    => $registro
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
                "success" => true,
                "message" => "Listado de Marca ",
                "data"    => Marca::findOrFail($id)
            ],200);
        }catch(ModelNotFoundException $e){

            return response()->json([
                "success" => false,
                "data"    => null,
                "message" => "No existe marca con el ID ".$id
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

       $input = $request->only(['nombre', 'descripcion']);
       Helper::sendErrorUpdate( $id, $request, $input );

       /* if( !is_numeric($id) ){
            return response()->json([
                "success" => false,
                "message" => " El parametro Id de la marca debe ser numerico ",
                ],404);
        }

        if ( count( $request->all() ) === 0 || !$input || count( $input ) === 0  ){
            return response()->json([
                "success" => false,
                "message" => "Parametros no validos",
                ],404);
        }*/


        try{
            $marca = Marca::findOrFail($id);

            if( $marca->update( $input ) ){

                return response()->json([
                    "success" => true,
                    "message" => "Marca Actualizada",
                    "data"    => $marca
                ]);

            }else{

                return response()->json([
                    "success" => false,
                    "message" => "Error al actualizar la Marca",
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => "Marca No encontrada",
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
