<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubcategoriaArticuloRequest extends FormRequest
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

    /*protected function prepareForValidation()
    {
        $this->merge([
        'slug' => Str::slug($this->slug),
        ]);
    }*/
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return match( $this->method() ){
            'POST'=> [
                'nombre'       => 'required|max:255|unique:subcategoria_articulos',
                'descripcion'  => 'string|nullable',
                'categoria'    => 'required|integer|exists:App\Models\CategoriaArticulo,id',
                'tipo_cantidad'=> 'required|string|in:lote,unidad'

            ],
            'PUT' => [
                //'id'          => 'required|int|exists:articulos,id',
                'nombre'      => 'string|unique:subcategoria_articulos',
                'descripcion' => 'required|string'
            ]
        };
    }
}
