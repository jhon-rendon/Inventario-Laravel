<?php

namespace App\Http\Requests;

use APP\Http\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'nombres'   => 'required|min:3|max:5',
            'apellidos' => 'required|min:3|max:5',
            'documento' => 'required|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido'
        ];
    }

    public function failedValidation( Validator $validator ){

         Helper::sendError( 'Validation Error', $validator->errors());
    }


}
