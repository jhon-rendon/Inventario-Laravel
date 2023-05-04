<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class MarcaRequest extends FormRequest
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
                'nombre'      => 'required|unique:articulos',
                'descripcion' => 'required'
            ],
            'PUT' => [
                //'id'          => 'required|int|exists:articulos,id',
                'nombre'      => 'string|unique:marcas',
                'descripcion' => 'string|nullable'
              ]
        };
     }
}
