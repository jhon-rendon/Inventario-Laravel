<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;

use App\Models\KardexArticulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\KardexArticulosRequest;

class KardexArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kardexArticulos =  KardexArticulo::all();
        return $kardexArticulos;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KardexArticulosRequest $request)
    {

        $kardexArticulos = new KardexArticulo();
        $kardexArticulos->modelo                    =  $request->input('modelo');
        $kardexArticulos->descripcion               =  $request->input('descripcion');
        $kardexArticulos->activo                    =  $request->input('activo');
        $kardexArticulos->serial                    =  $request->input('serial');
        $kardexArticulos->marcas_id                 =  $request->input('marca');
        $kardexArticulos->subcategoria_articulos_id =  $request->input('subcategoria');
        $kardexArticulos->save();

        return response()->json([
            "success" => true,
            "message" => "¡Registro Kardex Articulo exitoso!",
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
            $articulo = KardexArticulo::findOrFail($id);
            return $articulo;
        }
        // catch(Exception $e) catch any exception
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => "Subcategoria No encontrada",
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
    public function update(KardexArticulosRequest $request, $id)
    {
        $input = $request->only(['modelo',
                                'descripcion',
                                'serial',
                                'activo',
                                'ubicacion_actual',
                                'estado_actual',
                                //'categoria',
                                'marca',
                                'subcategoria'
                                ]);
        Helper::sendErrorUpdate( $id, $request, $input );

        try{
            $kardexArticulos = KardexArticulo::findOrFail($id);

            $kardexArticulos->modelo                    = ( !empty( $request->input('modelo')) ) ? $request->input('modelo') : $kardexArticulos->modelo;
            $kardexArticulos->descripcion               = ( !empty( $request->input('descripcion')) ) ? $request->input('descripcion') : $kardexArticulos->descripcion;
            $kardexArticulos->activo                    = ( !empty( $request->input('activo')) ) ? $request->input('activo') : $kardexArticulos->activo;
            $kardexArticulos->serial                    = ( !empty( $request->input('serial')) ) ? $request->input('serial') : $kardexArticulos->serial;
            $kardexArticulos->ubicacion_actual          = ( !empty( $request->input('ubicacion_actual')) ) ? $request->input('ubicacion_actual') : $kardexArticulos->ubicacion_actual;
            $kardexArticulos->estado_actual             = ( !empty( $request->input('estado_actual')) ) ? $request->input('estado_actual') : $kardexArticulos->estado_actual;
            $kardexArticulos->marcas_id                 = ( !empty( $request->input('marca')) ) ? $request->input('marca') : $kardexArticulos->marcas_id;
            $kardexArticulos->subcategoria_articulos_id = ( !empty( $request->input('subcategoria')) ) ? $request->input('subcategoria') : $kardexArticulos->subcategoria_articulos_id;
            //$kardexArticulos->categoria_articulos_id    = ( !empty( $request->input('categoria')) ) ? $request->input('categoria') : $kardexArticulos->categoria_articulos_id;

            if( $kardexArticulos->save() ){

                return response()->json([
                    "success" => true,
                    "message" => "kardexArticulos Actualizada",
                    "data" => $kardexArticulos
                ]);

            }else{

                return response()->json([
                    "success" => false,
                    "message" => "Error al actualizar el kardexArticulos",
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => "kardexArticulos No encontrado",
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
