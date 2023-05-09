<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstadoArticuloRequest extends FormRequest
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
                'estado'   => 'required|unique:estado_articulo',
            ],
            'PUT' => [
                'estado'   => 'required|unique:estado_articulo,estado,'.$this->estado_articulo.'',
            ]
        };
    }
}
