<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrasladoArticuloRequest;
use App\Models\DetalleTrasladoArticulo;
use App\Models\KardexArticulo;
use App\Models\KardexUbicacion;
use App\Models\TrasladoArticulo;
//use App\Models\TrasladoArticulo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrasladoArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request  )
    {
        if( !$request->query('paginate') || $request->query('paginate') !== 'false' ){
            //$kardexArticulos = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion'])->paginate(10);
            $trasladoArticulo  = DetalleTrasladoArticulo::with(['articulo'])->paginate(10);
        }
        else{
            //$kardexArticulos = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion'])->get();
            $trasladoArticulo  = DetalleTrasladoArticulo::with(['articulo'])->paginate(10);
        }
        return $trasladoArticulo;
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
    public function store(TrasladoArticuloRequest $request )//TrasladoArticuloRequest $request)
    {


        /*return response()->json([
            //"request" => $request->all(),
            "gettype" =>  $request->articulo,
            "articulo" => gettype($request->ubicacion)
            //"cantidad" => $request->input("articulo")->cantidad
        ]);*/

        DB::beginTransaction();

        try {

            $trasladoArticulo                      = new TrasladoArticulo();
            $trasladoArticulo->usuario_id          =  1;
            $trasladoArticulo->fecha               = Carbon::now()->toDateString();
            $trasladoArticulo->hora                = Carbon::now()->toTimeString();
            $trasladoArticulo->descripcion         = '';


            if( $trasladoArticulo->save() ){

                $idTraslado        = $trasladoArticulo->id;
                $ubicacion_destino = $request->input('ubicacion_destino');

                foreach ( $request->articulo as $articulo ) {

                    $articulo_id   = $articulo['articulo_id'];

                    $detalletrasladoArticulo                         =  new DetalleTrasladoArticulo();
                    $detalletrasladoArticulo->traslados_articulos_id = $idTraslado;
                    $detalletrasladoArticulo->ticket                 =  $articulo['ticket'];
                    $detalletrasladoArticulo->descripcion            =  $articulo['descripcion'];
                    $detalletrasladoArticulo->cantidad               =  $articulo['cantidad'];
                    $detalletrasladoArticulo->ubicacion_origen       =  $articulo['ubicacion_origen'];
                    $detalletrasladoArticulo->ubicacion_destino      =  $ubicacion_destino;
                    $detalletrasladoArticulo->estado_articulo_id     =  $articulo['estado'];
                    $detalletrasladoArticulo->kardex_articulos_id    =  $articulo_id;
                    $detalletrasladoArticulo->usuario_id             =  1;
                    $detalletrasladoArticulo->fecha                  =  Carbon::now()->toDateString();
                    $detalletrasladoArticulo->hora                   =  Carbon::now()->toTimeString();

                    if( $detalletrasladoArticulo->save() ) {

                        $kardexUbicacionOrigen  =  KardexUbicacion::where('kardex_articulos',$articulo_id)
                                                                    ->where('ubicacion_id',$articulo['ubicacion_origen'])->first();

                        $kardexUbicacionDestino  = KardexUbicacion::where('kardex_articulos',$articulo_id)
                                                                    ->where('ubicacion_id',$ubicacion_destino)->first();

                        //Restar Cantidad a la bodega Origen
                        $kardexUbicacionOrigen->cantidad  = ( $kardexUbicacionOrigen->cantidad - $articulo['cantidad']);
                        $kardexUbicacionOrigen->update();

                        if( $kardexUbicacionDestino || count( (array) $kardexUbicacionDestino ) > 0 ){
                            //Actualizar kardex Destino

                            //Sumar Cantidad a la bodega Destino
                            $kardexUbicacionDestino->cantidad  = ( $kardexUbicacionDestino->cantidad + $articulo['cantidad'] );
                            $kardexUbicacionDestino->update();
                        }else{
                            //Crear kardex Destino
                            $kardexUbiacion                    = new KardexUbicacion();
                            $kardexUbiacion->cantidad          = $articulo['cantidad'];
                            $kardexUbiacion->ubicacion_id      = $ubicacion_destino;
                            $kardexUbiacion->kardex_articulos  = $articulo_id;
                            $kardexUbiacion->save();

                        }

                        $kardexArticulo = KardexArticulo::with(['subcategoria.categoria'])->where('id',$articulo_id)->first();
                        //$kardexArticulo = KardexArticulo::where('id',$request->input('articulo_id'))->first();
                        if( $kardexArticulo->subcategoria->tipo_cantidad == 'unidad'){
                            $kardexArticulo->ubicacion_actual = $ubicacion_destino;
                            $kardexArticulo->estado_actual    = $request->input('estado');
                            $kardexArticulo->update();
                        }
                    }

                }//fin foreach
                // Commit de la transacción si todo se insertó correctamente
                DB::commit();
                return response()->json([
                    "success" => true,
                    "message" => "¡Registro del Traslado exitoso!",
                ]);

            }//fin if


        }   catch (Exception $e) {
            DB::rollBack();
            // Ocurrió una excepción general
            return response()->json([
                "success" => false,
                "message" => "Error al registrar el Traslado",
                "errors"  => $e->getMessage()
            ],500);
        }






        /*try{

            $trasladoArticulo                      = new TrasladoArticulo();
            $trasladoArticulo->usuario_id          =  1;
            $trasladoArticulo->fecha               = Carbon::now()->toDateString();
            $trasladoArticulo->hora                = Carbon::now()->toTimeString();
            $trasladoArticulo->descripcion         = $request->input('descripcion');

            if( $trasladoArticulo->save() ){

                $idTraslado   = $trasladoArticulo->id;

                $detalletrasladoArticulo                         =  new DetalleTrasladoArticulo();
                $detalletrasladoArticulo->traslados_articulos_id = $idTraslado;
                $detalletrasladoArticulo->ticket                 =  $request->input('ticket');
                $detalletrasladoArticulo->descripcion            =  $request->input('descripcion');
                $detalletrasladoArticulo->cantidad               =  $request->input('cantidad');
                $detalletrasladoArticulo->ubicacion_origen       =  $request->input('ubicacion_origen');
                $detalletrasladoArticulo->ubicacion_destino      =  $request->input('ubicacion_destino');
                $detalletrasladoArticulo->estado_articulo_id     =  $request->input('estado');
                $detalletrasladoArticulo->kardex_articulos_id    =  $request->input('articulo_id');
                $detalletrasladoArticulo->usuario_id             =  1;
                $detalletrasladoArticulo->fecha                  = Carbon::now()->toDateString();
                $detalletrasladoArticulo->hora                   = Carbon::now()->toTimeString();

                if( $detalletrasladoArticulo->save() ) {

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

                    $kardexArticulo = KardexArticulo::with(['subcategoria.categoria'])->where('id',$request->input('articulo_id'))->first();
                    //$kardexArticulo = KardexArticulo::where('id',$request->input('articulo_id'))->first();
                    if( $kardexArticulo->subcategoria->tipo_cantidad == 'unidad'){
                         $kardexArticulo->ubicacion_actual = $request->input('ubicacion_destino');
                         $kardexArticulo->estado_actual    = $request->input('estado');
                         $kardexArticulo->update();
                    }

                    // Commit de la transacción si todo se insertó correctamente
                    DB::commit();
                    return response()->json([
                        "success" => true,
                        "kardexUbicacionDestino" => $kardexUbicacionDestino,
                        "kardexUbicacionORIGEN"  => $kardexUbicacionOrigen,
                        "Nuevo Destino"          => ( isset( $kardexUbiacion))? $kardexUbiacion : null,
                        "message" => "¡Registro del Traslado exitoso!",
                    ]);

                }

                DB::rollBack();
                return response()->json([
                    "success" => false,
                    "message" => "Error al registrar el detalle del traslado",
                    "errors"  => "Error al registrar el detalle del traslado",
                ],500);

            }

            else{
                DB::rollBack();
                return response()->json([
                    "success" => false,
                    "message" => "Error al registrar el traslado",
                    "errosr"  => "Error al registrar el traslado",
                ],500);
            }
        }

        catch (Exception $e) {
            DB::rollBack();
            // Ocurrió una excepción general
            return response()->json([
                "success" => false,
                "message" => "Error al registrar el Traslado",
                "errors"  => $e->getMessage()
            ],500);
        }*/


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
