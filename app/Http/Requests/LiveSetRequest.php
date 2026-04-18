<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiveSetRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $songs = array_values(array_unique(array_filter(
            array_map(
                static fn ($songId) => is_numeric($songId) ? (int) $songId : null,
                (array) $this->input('songs', [])
            ),
            static fn ($songId) => $songId !== null
        )));

        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'songs' => $songs,
        ]);
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'songs' => ['required', 'array', 'min:1'],
            'songs.*' => ['integer', 'distinct', 'exists:songs,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'songs.required' => 'Select at least one song for the live set.',
            'songs.min' => 'Select at least one song for the live set.',
        ];
    }
}
