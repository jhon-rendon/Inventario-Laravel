<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoriaArticuloRequest;
use App\Models\SubCategoriaArticulo;
use Illuminate\Http\Request;

class SubcategoriaArticuloController extends Controller
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
