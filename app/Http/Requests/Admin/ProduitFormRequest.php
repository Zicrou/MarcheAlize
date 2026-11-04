<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProduitFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'designation' => ['required', 'string', 'min:2'],
            'nombre' => ['required', 'integer', 'min:1'],
            'montant' => ['required', 'integer', 'min:3'],
            'image' => ['mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            // 'etat' => ['required', 'boolean'],
        ];
    }
}
