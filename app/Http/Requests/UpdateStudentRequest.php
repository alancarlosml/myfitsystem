<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student');

        return [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:students,cpf,'.$studentId,
            'email' => 'required|string|email|max:255|unique:students,email,'.$studentId,
            'birthdate' => 'required|date',
            'password' => 'nullable|required_if:password_confirmation,filled|string',
            'password_confirmation' => 'nullable|same:password',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|string|max:255',
            'gender' => 'required|in:masculino,feminino,outro',
            'active' => 'nullable|boolean',
        ];
    }
}
