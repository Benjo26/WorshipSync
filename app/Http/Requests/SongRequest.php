<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SongRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $structure = array_values(array_filter(array_map(
            static fn (?string $value) => trim((string) $value),
            explode(',', (string) $this->input('structure_csv', ''))
        )));

        $chart = json_decode((string) $this->input('chart_json_raw', ''), true);

        $this->merge([
            'structure' => $structure,
            'chart_json' => is_array($chart) ? $chart : null,
        ]);
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['nullable', 'string', 'max:255'],
            'default_key' => ['required', 'string', 'max:10'],
            'bpm' => ['required', 'integer', 'min:40', 'max:240'],
            'time_signature' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
            'structure' => ['required', 'array', 'min:1'],
            'structure.*' => ['required', 'string', 'max:50'],
            'chart_json' => ['required', 'array'],
            'chart_json.sections' => ['required', 'array', 'min:1'],
            'chart_json.sections.*.name' => ['required', 'string', 'max:100'],
            'chart_json.sections.*.lines' => ['required', 'array', 'min:1'],
            'chart_json.sections.*.lines.*' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'chart_json.required' => 'The chord chart must be valid JSON.',
            'chart_json.array' => 'The chord chart must decode into a JSON object.',
            'structure.min' => 'Add at least one section to the song structure.',
        ];
    }
}
