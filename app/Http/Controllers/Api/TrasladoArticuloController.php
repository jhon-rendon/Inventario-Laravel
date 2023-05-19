<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrasladoArticuloRequest;
use App\Models\KardexArticulo;
use App\Models\KardexUbicacion;
use App\Models\TrasladoArticulo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrasladoArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(TrasladoArticuloRequest $request)
    {

        $trasladoArticulo                      = new TrasladoArticulo();
        $trasladoArticulo->ticket              =  $request->input('ticket');
        $trasladoArticulo->descripcion         =  $request->input('descripcion');
        $trasladoArticulo->cantidad            =  $request->input('cantidad');
        $trasladoArticulo->ubicacion_origen    =  $request->input('ubicacion_origen');
        $trasladoArticulo->ubicacion_destino   =  $request->input('ubicacion_destino');
        $trasladoArticulo->estado_articulo_id  =  $request->input('estado');
        $trasladoArticulo->kardex_articulos_id =  $request->input('articulo_id');
        $trasladoArticulo->usuario_id          =  1;
        $trasladoArticulo->fecha               = Carbon::now()->toDateString();
        $trasladoArticulo->hora                = Carbon::now()->toTimeString();

        if( $trasladoArticulo->save() ) {

            $kardexUbicacionOrigen  =  KardexUbicacion::where('kardex_articulos',$request->input('articulo_id'))
                                                        ->where('ubicacion_id',$request->input('ubicacion_origen'))->first();

            $kardexUbicacionDestino  = KardexUbicacion::where('kardex_articulos',$request->input('articulo_id'))
                                                        ->where('ubicacion_id',$request->input('ubicacion_destino'))->first();

            //Restar Cantidad a la bodega Origen
            $kardexUbicacionOrigen->cantidad  = ( $kardexUbicacionOrigen->cantidad - $request->input('cantidad') );
            $kardexUbicacionOrigen->update();

            if( $kardexUbicacionDestino || count( (array) $kardexUbicacionDestino ) > 0 ){
                //Actualizar kardex Destino

                //Sumar Cantidad a la bodega Destino
                $kardexUbicacionDestino->cantidad  = ( $kardexUbicacionDestino->cantidad + $request->input('cantidad') );
                $kardexUbicacionDestino->update();
            }else{
                //Crear kardex Destino
                $kardexUbiacion                    = new KardexUbicacion();
                $kardexUbiacion->cantidad          = $request->input('cantidad');
                $kardexUbiacion->ubicacion_id      = $request->input('ubicacion_destino');
                $kardexUbiacion->kardex_articulos  = $request->input('articulo_id');
                $kardexUbiacion->save();

            }

            $kardexArticulo = KardexArticulo::where('id',$request->input('articulo_id'))->first();
            $kardexArticulo->ubicacion_actual = $request->input('ubicacion_destino');
            $kardexArticulo->estado_actual    = $request->input('estado');
            $kardexArticulo->update();


            return response()->json([
                "success" => true,
                "kardexUbicacionDestino" => $kardexUbicacionDestino,
                "kardexUbicacionORIGEN"  => $kardexUbicacionOrigen,
                "Nuevo Destino"          => ( isset( $kardexUbiacion))? $kardexUbiacion : null,
                "message" => "¡Registro del Traslado exitoso!",
            ]);

        }

        return response()->json([
            "success" => false,
            "message" => "Error al registrar el traslado",
            "error"   => "Error al registrar el traslado",

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
        //
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
    public function update(Request $request, $id)
    {
        //
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
