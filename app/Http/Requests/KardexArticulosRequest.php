<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KardexArticulosRequest extends FormRequest
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
                'descripcion'                  => 'string|nullable',
                'modelo'                       => 'string|nullable',
                'tipo_cantidad'                => 'nullable|in:lote,unidad',
                'cantidad'                     => 'required|integer',
                'serial'                       => 'required_if:tipo_cantidad,unidad',
                'activo'                       => 'string|nullable',
                'marca'                        => 'required|integer|exists:App\Models\Marca,id',
                'subcategoria'                 => 'required|integer|exists:App\Models\SubCategoriaArticulo,id',
                'estado'                       => 'required_if:tipo_cantidad,unidad',
                'ubicacion_destino'            => 'required|integer|exists:App\Models\Ubicacion,id',
                //'categoria'                    => 'required|integer|exists:App\Models\CategoriaArticulo,id',
            ],
            'PUT' => [
                'descripcion'                  => 'string|nullable',
                'modelo'                       => 'string|nullable',
                'serial'                       => 'string|nullable',
                'activo'                       => 'string|nullable',
                'marca'                        => 'integer|exists:App\Models\Marca,id',
                'subcategoria'                 => 'integer|exists:App\Models\SubCategoriaArticulo,id',
                //'categoria'                    => 'required|integer|exists:App\Models\CategoriaArticulo,id',
                'ubicacion_actual'             => 'integer|exists:App\Models\Ubicacion,id',
                'estado_actual'                => 'integer|exists:App\Models\EstadoArticulo,id',
            ]
        };
    }
}
