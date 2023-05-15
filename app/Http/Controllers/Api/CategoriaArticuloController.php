<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\CategoriaArticuloRequest;
use App\Models\CategoriaArticulo;
use Illuminate\Http\Request;

class CategoriaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

       /*$this->middleware('role_or_permission:articulo_index', ['only' => ['index']]);
       $this->middleware('role_or_permission:articulo_create', ['only' => ['store']]);
       $this->middleware('role_or_permission:articulo_show', ['only' => ['show']]);
       $this->middleware('role_or_permission:articulo_edit', ['only' => ['update']]);*/


       /*$this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
       $this->middleware('permission:role-create', ['only' => ['create','store']]);
       $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
       $this->middleware('permission:role-delete', ['only' => ['destroy']]);*/
    }

    public function index(Request $request)
    {

        if( !$request->query('paginate') || $request->query('paginate') !== 'false' ){
            $categoria = CategoriaArticulo::orderBy('nombre', 'asc')->paginate(10);
        }
        else{
            $categoria = CategoriaArticulo::orderBy('nombre', 'asc')->get();
        }
        return $categoria;
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
            "success" => true,
            "message" => "¡Registro de articulo exitoso!",
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
        Helper::sendErrorShow($id);

       try
        {
            $articulo = CategoriaArticulo::findOrFail($id);
            return $articulo;
        }
        // catch(Exception $e) catch any exception
        catch(ModelNotFoundException $e)
        {

            return response()->json([
                "success" => false,
                "message" => "Categoria No encontrada",
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


       $input = $request->only(['nombre', 'descripcion']);
       Helper::sendErrorUpdate( $id, $request, $input );

        try{
            $articulo = CategoriaArticulo::findOrFail($id);

            if( $articulo->update($request->all()) ){

                return response()->json([
                    "success" => true,
                    "message" => "Arciculo Actualizado",
                    "data" => $articulo
                ]);

            }else{

                return response()->json([
                    "success" => false,
                    "message" => "Error al actualizar el articulo",
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => "Articulo No encontrado",
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
