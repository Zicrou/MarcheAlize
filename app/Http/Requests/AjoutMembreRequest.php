<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AjoutMembreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                'exists:users,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:255',
                'exists:users,phone',
            ],
        ];
    }
}
