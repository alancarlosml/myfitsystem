<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user'); 

        return [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users,cpf,' . $userId,
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|required_if:password_confirmation,filled|string|min:8',
            'password_confirmation' => 'nullable|same:password',
            'phone' => 'nullable|string|max:15',
            'active' => 'boolean',

            'profile_picture' => 'nullable|string', 
            'academic_degree' => 'nullable|string', 
            'professional_experience' => 'nullable|string',
        ];
    }
}
