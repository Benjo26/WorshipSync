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

        $this->merge([
            'structure' => $structure,
            'chordpro' => trim((string) $this->input('chordpro', '')),
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
            'chordpro' => ['required', 'string', 'min:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'chordpro.required' => 'Add the song chart in ChordPro format.',
            'structure.min' => 'Add at least one section to the song structure.',
        ];
    }
}
