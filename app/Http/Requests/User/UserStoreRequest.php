<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'names' => ['required', 'string', 'min:5', 'max:50'],
            'last_names' => ['required', 'string', 'min:5', 'max:50'],
            'number_phone' => ['required', 'digits:10', 'numeric'],
            'email' => ['required', 'email', 'min:5', 'max:80', 'unique:users,email'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'digits' => 'El campo :attribute debe tener :digits digitos.',
            'numeric' => 'El campo :attribute debe ser numerico.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
            'unique' => 'El campo :attribute ya ha sido tomado.'
        ];
    }

    public function attributes()
    {
        return [
            'names' => 'nombres',
            'last_names' => 'apellidos',
            'number_phone' => 'numero de telefono',
            'email' => 'correo electrónico',
        ];
    }
}
