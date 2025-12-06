<?php

namespace App\Http\Requests;

use Carbon\Carbon;
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove máscaras de CPF e telefone
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->cpf)
            ]);
        }

        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D/', '', $this->phone)
            ]);
        }

        // Converte a data do formato brasileiro (d/m/Y) para o formato do banco (Y-m-d)
        if ($this->has('birthdate') && !empty($this->birthdate)) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $this->birthdate);
                $this->merge([
                    'birthdate' => $date->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                try {
                    $date = Carbon::parse($this->birthdate);
                    $this->merge([
                        'birthdate' => $date->format('Y-m-d')
                    ]);
                } catch (\Exception $e2) {
                    // Mantém o valor original se não conseguir converter
                }
            }
        }
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
