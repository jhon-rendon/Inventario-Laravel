<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\CategoriaArticuloRequest;
use App\Models\CategoriaArticulo;
use Illuminate\Support\Facades\Gate;


class CategoriaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // abort_if(Gate::denies('articulo_index'), 403);

        //$articulos = Articulo::paginate(5);

        $articulos = CategoriaArticulo::all();
        return $articulos;
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
    public function store(CategoriaArticuloRequest $request)
    {


        /*$articulo = new Articulo();
        $articulo->nombre      = $request->nombre;
        $articulo->descripcion = $request->descripcion;
        $articulo->save();*/

        $data = $request->all();
        CategoriaArticulo::create($data);

        return response()->json([
            "status" => 1,
            "msg" => "¡Registro de articulo exitoso!",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        /*if( !is_numeric($id))

        return response()->json([
            "status" => false,
            "msg" => "El ID debe ser numerico",
        ],404);*/

        try
        {
            $articulo = CategoriaArticulo::findOrFail($id);
            return $articulo;
        }
        // catch(Exception $e) catch any exception
        catch(ModelNotFoundException $e)
        {

            return response()->json([
                "status" => false,
                "msg" => "Articulo No encontrado",
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
    public function update(CategoriaArticuloRequest $request, $id )
    {

        try{
            $articulo = CategoriaArticulo::findOrFail($id);

            if( $articulo->update($request->all()) ){

                return response()->json([
                    "status" => true,
                    "msg" => "Arciculo Actualizado",
                    "articulo" => $articulo
                ]);

            }else{

                return response()->json([
                    "status" => false,
                    "msg" => "Error al actualizar el articulo",
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "status" => false,
                "msg" => "Articulo No encontrado",
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
