<?php

namespace App\Http\Requests;

use App\Models\Ubicacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UbicacionRequest extends FormRequest
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
                'nombre'            => 'required|max:255|unique:ubicacion',
                'codigo'            => 'string|nullable',
                'tipo_ubicacion_id' => 'required|integer|exists:App\Models\TipoUbicacion,id',
                'direccion'         => 'string|nullable'

            ],
            'PUT' => [
                'nombre'            => 'required|min:3|max:255|unique:ubicacion,nombre,'.$this->ubicacion.'',
                'codigo'            => 'string|nullable',
                'tipo_ubicacion_id' => 'required|integer|exists:App\Models\TipoUbicacion,id',
                'direccion'         => 'string|nullable'
            ]
        };
    }
}
