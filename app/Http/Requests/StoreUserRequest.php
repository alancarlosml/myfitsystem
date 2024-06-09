<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users,cpf',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'nullable|required_if:password_confirmation,filled|string',
            'password_confirmation' => 'nullable|same:password',
            'phone' => 'nullable|string|max:15',
            'active' => 'boolean',

            'profile_picture' => 'nullable|string', 
            'academic_degree' => 'nullable|string', 
            'professional_experience' => 'nullable|string',
        ];
    }
}
