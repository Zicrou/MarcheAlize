<?php

namespace App\Http\Requests\api\v1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OperationFormRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'integer', 'min:1'],
            'prix' => ['required', 'decimal:2'],
            'designation' => ['string', 'nullable'],
            'produit_id' => ['string',  'nullable'],
            'types' => ['required', 'string'],
            'image' => ['mimes:jpg,jpeg,png,webp'],
        ];
    }
}
