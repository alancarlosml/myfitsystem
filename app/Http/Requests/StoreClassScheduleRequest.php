<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassScheduleRequest extends FormRequest
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
        // Converte a data do formato brasileiro (d/m/Y) para o formato do banco (Y-m-d)
        if ($this->has('class_date') && !empty($this->class_date)) {
            try {
                // Tenta fazer o parse da data no formato brasileiro
                $date = Carbon::createFromFormat('d/m/Y', $this->class_date);
                $this->merge([
                    'class_date' => $date->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                // Se falhar, tenta com outros formatos comuns
                try {
                    $date = Carbon::parse($this->class_date);
                    $this->merge([
                        'class_date' => $date->format('Y-m-d')
                    ]);
                } catch (\Exception $e2) {
                    // Mantém o valor original se não conseguir converter
                }
            }
        }
    }

    public function rules(): array
    {
        return [
            'modality_id' => 'required|exists:modalities,id',
            'establishment_id' => 'required|exists:establishments,id',
            'description' => 'nullable|string',
            'class_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }
}
