<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;

use App\Models\KardexArticulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\KardexArticulosRequest;
use App\Models\DetalleTrasladoArticulo;
use App\Models\KardexUbicacion;
use App\Models\TrasladoArticulo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class KardexArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        //$kardexArticulos =  KardexArticulo::all();
        //return $kardexArticulos;

       /* $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        $sortBy = $request->input('sortBy', $column);
        $orderBy = $request->input('orderBy', $dir);
        $searchValue = $request->input('search');

        $query = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion'])->orderBy($sortBy, $orderBy);

        // Aplicar búsqueda
        if ($searchValue) {
            $query->where(function ($innerQuery) use ($searchValue) {
                $innerQuery->where('serial', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('modelo', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('id', 'LIKE', '%' . $searchValue . '%');
                    });
        }

        // Obtener los resultados paginados
         $users = $query->paginate($length);

        return response()->json($users);*/

        //$query = KardexArticulo::eloquentQuery($sortBy, $orderBy, $searchValue);

        //$data = $query->paginate($length);

        //return new DataTableCollectionResource($query);

        /*if( !$request->query('paginate') || $request->query('paginate') !== 'false' ){
            $kardexArticulos = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion'])->paginate(10);
        }
        else{
            $kardexArticulos = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion'])->get();
        }
        return $kardexArticulos;
        */
        $length      = ( !empty( $request->input('size'))  )  ?  $request->input('size') : 10;
        $searchValue = ( !empty( $request->input('search')) ) ?  strtoupper( $request->input('search') ) : null;




        if ($searchValue) {

            $kardexArticulos = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion']);

            $kardexArticulos->whereRaw('UPPER(serial) LIKE (?)',["%{$searchValue}%"])
                            ->orWhereRaw('UPPER(modelo) LIKE (?)', ["%{$searchValue}%"])
                            ->orWhereRaw('id LIKE (?)', $searchValue)
                            ->orWhereRaw('activo LIKE (?)', $searchValue)
                            ->orWhereHas('subcategoria.categoria', function ( $query ) use ($searchValue)  {
                                $query->whereRaw('UPPER(nombre)  LIKE (?)', ["%{$searchValue}%"]);
                            })
                            ->orWhereHas('subcategoria', function ( $query ) use ($searchValue)  {
                                $query->whereRaw('UPPER(nombre)  LIKE (?)', ["%{$searchValue}%"]);
                            })
                            ->orWhereHas('marcas', function ( $query ) use ($searchValue)  {
                                $query->whereRaw('UPPER(nombre)  LIKE (?)', ["%{$searchValue}%"]);
                            })
                            ->orWhereHas('kardexUbicacion.ubicacion', function ( $query ) use ($searchValue)  {
                                $query->whereRaw('UPPER(nombre)  LIKE (?)', ["%{$searchValue}%"]);
                            });
        }
        else{
            $kardexArticulos = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion']);
        }

        return $kardexArticulos->paginate($length);
    }

    public function getArticulosDisponiblesByCategoria( Request $request ){

        $subcategoria = $request->input('subcategoria');
        $ubicacion    = $request->input('ubicacion');

        /*$kardexArticulos = KardexArticulo::with(['marcas','kardexUbicacion'=> function($query) use($ubicacion){
                                         $query->withW('ubicacion.id','=',$ubicacion)
                                        ->whereRaw('ubicacion.cantidad','>',0);
                                        },'kardexUbicacion.ubicacion'])
                                        ->where('subcategoria_articulos_id','=',$subcategoria)->get();*/

        $kardexArticulos = KardexArticulo::with(['kardexUbicacion','marcas'])
                                        ->whereHas('kardexUbicacion', function($query) use($ubicacion) {
                                            $query->where('cantidad','>', 0)->whereHas('ubicacion', function ($query) use($ubicacion)  {
                                                $query->where('id', $ubicacion);
                                            });
                                        })->where('subcategoria_articulos_id','=',$subcategoria)->get();
        return $kardexArticulos;
        //return $subcategoria;

    }

    public function getArticulosByUbicacion( Request $request ){

        $ubicacion    = $request->ubicacion;

        $kardexArticulos = KardexArticulo::with(['kardexUbicacion' =>
                        function($query) use($ubicacion) {
                            $query->where('cantidad','>', 0)->where('ubicacion_id','=',$ubicacion);
                        },'marcas','subcategoria.categoria'])->get();
            /*->whereHas('kardexUbicacion', function($query) use($ubicacion) {
                $query->where('cantidad','>', 0)->where('ubicacion_id','=',$ubicacion);
            })*///->get();
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

        if( $request->input('tipo_cantidad') === 'lote' ){
            $cantidad         = $request->input('cantidad');
            $ubicacionActual  = null;
            $estadoActual     = null;
        }else if( $request->input('tipo_cantidad') === 'unidad' ){
            $cantidad        = 1;
            $ubicacionActual = $request->input('ubicacion_destino');
            $estadoActual    = $request->input('estado');
        }

        DB::beginTransaction();
        try{
             /*$validArticulo = KardexArticulo::where('subcategoria_articulos_id','=',$request->input('subcategoria'))
                                     ->where('marcas_id','=',$request->input('marca'))->first();


            if( $validArticulo ){

                $id_kardex_articulo = $validArticulo->id;
                $kardexUbicacion    = KardexUbicacion::where('kardex_articulos','=',$id_kardex_articulo)
                                                         ->where('ubicacion_id','=',$request->input('ubicacion_destino'))->first();
            }else{
                $kardexUbicacion = null;
            }


            if( ( $request->input('tipo_cantidad') === 'unidad' ) OR
                ( !$validArticulo && $request->input('tipo_cantidad') === 'lote' ) ){

                //Crear Nuevo Registro
                $kardexArticulos = new KardexArticulo();
                $kardexArticulos->modelo                    =  $request->input('modelo');
                $kardexArticulos->descripcion               =  $request->input('descripcion');
                $kardexArticulos->activo                    =  $request->input('activo');
                $kardexArticulos->serial                    =  $request->input('serial');
                $kardexArticulos->marcas_id                 =  $request->input('marca');
                $kardexArticulos->subcategoria_articulos_id =  $request->input('subcategoria');
                $kardexArticulos->ubicacion_actual          =  $ubicacionActual;
                $kardexArticulos->estado_actual             =  $estadoActual;
                $kardexArticulos->save();

                $id_kardex_articulo = $kardexArticulos->id;

                //Crear Kardex Ubicacion
                $kardexUbicacion                       = new KardexUbicacion();
                $kardexUbicacion->cantidad             = $cantidad;
                $kardexUbicacion->ubicacion_id         = $request->input('ubicacion_destino');
                $kardexUbicacion->kardex_articulos     = $id_kardex_articulo;
                $kardexUbicacion->save();


            }else{

                if( !$kardexUbicacion && $validArticulo ){

                    //Crear Kardex Ubicacion
                    $kardexUbicacion                       = new KardexUbicacion();
                    $kardexUbicacion->cantidad             = $cantidad;
                    $kardexUbicacion->ubicacion_id         = $request->input('ubicacion_destino');
                    $kardexUbicacion->kardex_articulos     = $id_kardex_articulo;
                    $kardexUbicacion->save();
                }
                else{
                     //Actualizar  Kardex Ubicacion
                    $kardexUbicacion->cantidad  = $kardexUbicacion->cantidad + $cantidad;
                    $kardexUbicacion->update();
                }

            }*/

                $kardexArticulos = new KardexArticulo();
                $kardexArticulos->modelo                    =  $request->input('modelo');
                $kardexArticulos->descripcion               =  $request->input('descripcion');
                $kardexArticulos->activo                    =  $request->input('activo');
                $kardexArticulos->serial                    =  $request->input('serial');
                $kardexArticulos->marcas_id                 =  $request->input('marca');
                $kardexArticulos->subcategoria_articulos_id =  $request->input('subcategoria');
                $kardexArticulos->ubicacion_actual          =  $ubicacionActual;
                $kardexArticulos->estado_actual             =  $estadoActual;
                $kardexArticulos->save();

                $id_kardex_articulo = $kardexArticulos->id;

                 //Crear Kardex Ubicacion
                 $kardexUbicacion                       = new KardexUbicacion();
                 $kardexUbicacion->cantidad             = $cantidad;
                 $kardexUbicacion->ubicacion_id         = $request->input('ubicacion_destino');
                 $kardexUbicacion->kardex_articulos     = $id_kardex_articulo;
                 $kardexUbicacion->save();



            //if( $kardexArticulos->save() ||  ) {

                    $trasladoArticulo                      = new TrasladoArticulo();
                    $trasladoArticulo->usuario_id          =  1;
                    $trasladoArticulo->fecha               = Carbon::now()->toDateString();
                    $trasladoArticulo->hora                = Carbon::now()->toTimeString();
                    $trasladoArticulo->descripcion         = $request->input('descripcion');

                    if( $trasladoArticulo->save() ){

                        $idTraslado   = $trasladoArticulo->id;

                        $detalleTrasladoArticulo                         = new DetalleTrasladoArticulo();
                        $detalleTrasladoArticulo->traslados_articulos_id = $idTraslado;
                        $detalleTrasladoArticulo->estado_articulo_id     = $request->input('estado');
                        $detalleTrasladoArticulo->cantidad               = $request->input('cantidad');
                        $detalleTrasladoArticulo->ubicacion_origen       = $request->input('ubicacion_origen');
                        $detalleTrasladoArticulo->ubicacion_destino      = $request->input('ubicacion_destino');
                        $detalleTrasladoArticulo->ticket                 = $request->input('ticket');
                        $detalleTrasladoArticulo->usuario_id             = 1;
                        $detalleTrasladoArticulo->kardex_articulos_id    = $id_kardex_articulo;
                        $detalleTrasladoArticulo->fecha                  = Carbon::now()->toDateString();
                        $detalleTrasladoArticulo->hora                   = Carbon::now()->toTimeString();
                        $detalleTrasladoArticulo->save();

                        // Commit de la transacción si todo se insertó correctamente
                        DB::commit();
                        return response()->json([
                            "success" => true,
                            "message" => "El articulo se registro correctamente"
                        ]);

                    }




            /*}
            else{
                DB::rollBack();
                return response()->json([
                    "success" => false,
                    "message" => "Error al registrar el Articulo",
                    "errors"  => "Error al registrar el Articulo",
                ],500);
            }*/
        }
        /*catch(QueryException $e){
            // Revertir la transacción y eliminar el traslado completo
            DB::rollBack();
            return response()->json([
                    "success" => false,
                    "message" => "1Error al registrar el Articulo",
                    "error"   => "1Error al registrar el Articulo",
                    "e"       => $e->getMessage()
            ],500);

        }*/
        catch (Exception $e) {
            DB::rollBack();
            // Ocurrió una excepción general
            return response()->json([
                "success" => false,
                "message" => "Error al registrar el Articulo",
                "errors"  => $e->getMessage()

            ],500);
        }




        /*$subcategoriaById = SubCategoriaArticulo::find($request->input('subcategoria'));

        if( $subcategoriaById->tipo_cantidad === 'lote' ){

        }
        else if ( $subcategoriaById->tipo_cantidad === 'unidad' ){

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
        Helper::sendErrorShow($id);

       try
        {
            $articulo = KardexArticulo::with(['subcategoria.categoria','marcas','kardexUbicacion.ubicacion'])->findOrFail($id);
            return $articulo;
            return response()->json([
                "success" => true,
                "message" => "",
                "data"    => $articulo
            ],404);
        }
        // catch(Exception $e) catch any exception
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => "Articulo No encontrado",
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
