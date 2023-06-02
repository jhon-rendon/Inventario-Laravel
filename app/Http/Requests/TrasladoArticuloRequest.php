<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TrasladoArticuloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     //'required','integer','exists:App\Models\KardexArticulo,id',
    public function rules()
    {
        return match( $this->method() ){
            'POST'=> [
                'articulo' => 'required|array',
                //'cantidad'                     => 'required|integer',
                //'estado'                       => 'required|integer',
                'articulo.*.ubicacion_origen'  => 'required|integer|exists:App\Models\Ubicacion,id|different:ubicacion_destino',
                'ubicacion_destino'            => 'required|integer|exists:App\Models\Ubicacion,id|different:ubicacion_origen',
                'articulo.*.articulo_id'       => ['required','integer'],
                'articulo.*.cantidad'          => ['required','integer','min:1',

                    function ($attribute, $valueCantidad, $fail) {
                        $table = 'kardex_ubicacion'; // Reemplaza 'nombre_tabla' con el nombre de tu tabla
                        $index     = explode('.',$attribute)[1];
                        $ubicacion    = $this->input('articulo.*.ubicacion_origen');
                        $articulo_id  = $this->input('articulo.*.articulo_id');

                        $validKardex = DB::table($table)
                                        ->where('kardex_articulos',$articulo_id[$index])
                                        ->where('ubicacion_id',  $ubicacion[$index])
                                        ->first();

                        if( $validKardex && $validKardex->cantidad < $valueCantidad ){
                            $fail("La cantidad disponible es insuficiente");
                        }
                        else if( !$validKardex ){
                            $fail("No existe relación del articulo con la ubicación ");
                        }
                    }
                ],
                'articulo.*.estado'            => 'required|integer',
                'articulo.*.ticket'            => 'integer',
            ],
            /*'PUT' => [
                'descripcion'                  => 'string|nullable',
                'modelo'                       => 'string|nullable',
                'serial'                       => 'string|nullable',
                'activo'                       => 'string|nullable',
                'marca'                        => 'integer|exists:App\Models\Marca,id',
                'subcategoria'                 => 'integer|exists:App\Models\SubCategoriaArticulo,id',
                //'categoria'                    => 'required|integer|exists:App\Models\CategoriaArticulo,id',
                'ubicacion_actual'             => 'integer|exists:App\Models\Ubicacion,id',
                'estado_actual'                => 'integer|exists:App\Models\EstadoArticulo,id',
            ]*/

        };
    }

    public function messages(){

        return [
            'articulo.*.cantidad.required'    => 'La cantidad es obligatoria',
            'articulo.*.cantidad.integer'     => 'La cantidad debe ser numérica',
            'articulo.*.cantidad.min'         => 'La cantidad debe ser minino 1',
            'articulo.*.ticket.integer'       => 'El ticket debe ser numérico',
            'articulo.*.articulo_id.required' => 'Se debe enviar un articulo valido',
            'articulo.*.articulo_id.integer'  => 'El articulo es obligatorio',



        ];
    }

}
