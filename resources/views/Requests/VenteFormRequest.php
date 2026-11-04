<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenteFormRequest extends FormRequest
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
            'nombre' => ['required', 'integer', 'min:1'],
            'prix' => ['required', 'integer', 'min:3'],
            'user_id' => ['exists:users,id', 'required'],
            'designation' => ['string', 'nullable'],
            'produit_id' => ['integer',  'nullable'],
            'types' => ['required'],
            'image' => ['mimes:jpg,jpeg,png,webp'],
        ];
    }
}
