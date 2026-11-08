<?php

namespace App\Http\Requests\api\v1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', 'unique:users,email'],
            'phone' => ['required','string','max:20', 'unique:users,phone'],
            'password' => ['required','string','min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Le nom est obligatoire.',
            'name.min'          => 'Le nom doit contenir au moins :min caractères.',
            'email.required'    => 'L’email est obligatoire.',
            'email.email'       => 'Veuillez entrer un email valide.',
            'email.unique'      => 'Cet email est déjà utilisé.',
            'phone.required'    => 'Le numéro de téléphone est obligatoire.',
            'phone.unique'      => 'Ce numéro de téléphone est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins :min caractères.',
        ];
    }
}