<?php

namespace App\Http\Controllers\Api;

use App\Models\KardexUbicacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KardexUbicacionController extends Controller
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
    public function store(Request $request)
    {

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

    public function validKardexUbicacionArticulo( Request $request ){

        $request->validate([
            "id_articulo"    => "required|integer",
            "id_ubicacion"   => "required|integer",
        ]);

        $kardexUbicacion = KardexUbicacion::with('ubicacion')
                                        ->where('id', $request->input('id_ubicacion'))
                                        ->where('kardex_articulos',$request->input('id_articulo'))->first();

        if( $kardexUbicacion && count( (array) $kardexUbicacion) > 0 ){

            return response([
                "success" => true,
                "message" => "La ubicacion y el articulo existen y se encuentran relacionados",
                "data"    => $kardexUbicacion
            ]);
        }
        else{

            return response([
                "success" => false,
                "message" => "La ubicacion y el articulo no existen ó no se encuentran relacionados"
            ],404);
        }
    }
}
