<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoriaArticuloRequest;
use App\Models\SubCategoriaArticulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SubcategoriaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {

        if( !$request->query('paginate') || $request->query('paginate') !== 'false' ){
            $subcategoria = SubCategoriaArticulo::with('categoria')->paginate(10);
        }
        else{
            $subcategoria = SubCategoriaArticulo::with('categoria')->get();
        }

        return $subcategoria;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcategoriaArticuloRequest $request)
    {

        //$data = $request->all();
        $subcategoria = new SubCategoriaArticulo();
        $subcategoria->nombre                   = $request->input('nombre');
        $subcategoria->descripcion              = $request->input('descripcion');
        $subcategoria->categoria_articulos_id   = $request->input('categoria');
        $subcategoria->tipo_cantidad            = $request->input('tipo_cantidad');
        $subcategoria->save();

        //SubCategoriaArticulo::create($data);

        return response()->json([
            "success" => true,
            "message" => "¡Registro de Subcategoria exitosa!",
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
            $articulo = SubCategoriaArticulo::findOrFail($id);
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
    public function update(SubcategoriaArticuloRequest $request, $id)
    {
        $input = $request->only(['nombre', 'descripcion','tipo_cantidad','categoria']);
        Helper::sendErrorUpdate( $id, $request, $input );

        try{
            $subcategoria = SubCategoriaArticulo::findOrFail($id);

            $subcategoria->nombre                   = ( !empty( $request->input('nombre')) ) ? $request->input('nombre') : $subcategoria->nombre;
            $subcategoria->descripcion              = ( !empty( $request->input('descripcion')) ) ? $request->input('descripcion') : $subcategoria->descripcion;
            $subcategoria->categoria_articulos_id   = ( !empty( $request->input('categoria')) ) ? $request->input('categoria') : $subcategoria->categoria_articulos_id;
            $subcategoria->tipo_cantidad            = ( !empty( $request->input('tipo_cantidad')) ) ? $request->input('tipo_cantidad') : $subcategoria->tipo_cantidad;

            if( $subcategoria->save() ){

                return response()->json([
                    "success" => true,
                    "message" => "Subcategoria Actualizada",
                    "data" => $subcategoria
                ]);

            }else{

                return response()->json([
                    "success" => false,
                    "message" => "Error al actualizar la subcategoria",
                ],400);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                "success" => false,
                "message" => "Subcategoria No encontrada",
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
