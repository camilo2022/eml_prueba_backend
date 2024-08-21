<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserIndexRequest extends FormRequest
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
            'perPage' => ['required', 'numeric'],
            'column' => ['required', 'string', 'in:id,names,last_names,number_phone,email,created_at,updated_at,deleted_at'],
            'dir' => ['required', 'string', 'in:ASC,DESC'],
            'search' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'perPage.required' => 'El campo Numero de registros por página es requerido.',
            'perPage.numeric' => 'El campo Numero de registros por página debe ser un valor numérico.',
            'column.required' => 'El campo columna es requerido.',
            'column.string' => 'El campo columna debe ser una cadena de caracteres.',
            'column.in' => 'El campo columna debe ser un valor valido (id, names, last_names, number_phone, email, created_at, updated_at, deleted_at).',
            'dir.required' => 'El campo ordenacion es requerido.',
            'dir.string' => 'El campo ordenacion debe ser una cadena de caracteres.',
            'dir.in' => 'El campo ordenacion debe ser un valor valido (ASC, DESC).',
        ];
    }
}
