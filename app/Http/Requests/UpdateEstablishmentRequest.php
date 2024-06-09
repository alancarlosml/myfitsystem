<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstablishmentRequest extends FormRequest
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
        $establishmentId = $this->route('establishment');
        
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:academia,crossfit,personal_trainer',
            'owner' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255|unique:establishments,cnpj,'.$establishmentId,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'social_network' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ];
    }
}
