<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseRequest extends FormRequest
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
            'establishment_id' => 'required|exists:establishments,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exercise_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'establishment_id.required' => 'O campo estabelecimento é obrigatório.',
            'establishment_id.exists' => 'O estabelecimento selecionado não é válido.',
            'category_id.required' => 'O campo categoria é obrigatório.',
            'category_id.exists' => 'A categoria selecionada não é válida.',
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'exercise_picture.image' => 'O campo foto do exercício deve ser uma imagem.',
            'exercise_picture.mimes' => 'A foto do exercício deve ser um arquivo do tipo: jpeg, png, jpg, gif, svg.',
            'exercise_picture.max' => 'A foto do exercício não pode ter mais de 2048 kilobytes.',
            'active.required' => 'O campo ativo é obrigatório.',
            'active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
        ];
    }
}
