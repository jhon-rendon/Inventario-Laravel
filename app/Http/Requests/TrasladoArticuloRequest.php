<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        return match( $this->method() ){
            'POST'=> [
                'articulo' => 'required|array',
                //'cantidad'                     => 'required|integer',
                //'estado'                       => 'required|integer',
                'articulo.*.ubicacion_origen'  => 'required|integer|exists:App\Models\Ubicacion,id|different:ubicacion_destino',
                'ubicacion_destino'            => 'required|integer|exists:App\Models\Ubicacion,id|different:ubicacion_origen',
                'articulo.*.articulo_id'       => 'required|integer',
                'articulo.*.cantidad'          => 'required|integer|min:1',
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
            'articulo.*.cantidad.required' => 'La cantidad es obligatoria',
            'articulo.*.cantidad.integer'  => 'La cantidad debe ser numérica',
            'articulo.*.cantidad.min'      => 'La cantidad debe ser minino 1',

        ];
    }
}
