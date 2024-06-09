<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Ajuste conforme sua lógica de autorização
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'establishment_id' => 'required|exists:establishments,id',
            'user_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:students,id',
            'exercise_id' => 'required|exists:exercises,id',
            'order' => 'required|integer|min:1',
            'sets' => 'required|integer|min:1',
            'repetitions' => 'required|integer|min:1',
            'rest_time' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'active' => 'nullable|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'establishment_id.required' => 'O estabelecimento é obrigatório.',
            'establishment_id.exists' => 'O estabelecimento selecionado é inválido.',
            'user_id.required' => 'O usuário é obrigatório.',
            'user_id.exists' => 'O usuário selecionado é inválido.',
            'student_id.required' => 'O estudante é obrigatório.',
            'student_id.exists' => 'O estudante selecionado é inválido.',
            'exercise_id.required' => 'O exercício é obrigatório.',
            'exercise_id.exists' => 'O exercício selecionado é inválido.',
            'order.required' => 'A ordem é obrigatório.',
            'sets.required' => 'O número de séries é obrigatório.',
            'sets.integer' => 'O número de séries deve ser um número inteiro.',
            'sets.min' => 'O número de séries deve ser pelo menos 1.',
            'repetitions.required' => 'O número de repetições é obrigatório.',
            'repetitions.integer' => 'O número de repetições deve ser um número inteiro.',
            'repetitions.min' => 'O número de repetições deve ser pelo menos 1.',
            'rest_time.required' => 'O tempo de descanso é obrigatório.',
            'rest_time.integer' => 'O tempo de descanso deve ser um número inteiro.',
            'rest_time.min' => 'O tempo de descanso deve ser pelo menos 0.',
            'notes.string' => 'As observações devem ser uma string.',
            'active.boolean' => 'O campo ativo deve ser um valor booleano.',
        ];
    }
}
