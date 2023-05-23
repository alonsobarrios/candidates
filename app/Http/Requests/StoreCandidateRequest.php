<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'source' => 'required',
            'owner' => [
                'required',
                'exists:users,id'
            ]
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => "El campo 'name' es requerido.",
            'source.required' => "El campo 'source' es requerido.",
            'owner.required' => "El campo 'owner' es requerido.",
            'owner.exists' => "El campo 'owner' debe ser un usuario válido."
        ];
    }
}
