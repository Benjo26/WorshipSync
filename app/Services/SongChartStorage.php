<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SongChartStorage
{
    public function store(array $chart): string
    {
        $path = $this->path();

        Storage::disk('local')->put($path, json_encode($chart, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $path;
    }

    public function read(string $path): array
    {
        if (! Storage::disk('local')->exists($path)) {
            return ['sections' => []];
        }

        return json_decode(Storage::disk('local')->get($path), true) ?: ['sections' => []];
    }

    public function replace(string $path, array $chart): string
    {
        Storage::disk('local')->put($path, json_encode($chart, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk('local')->delete($path);
    }

    private function path(): string
    {
        return 'songs/' . Uuid::uuid7()->toString() . '.json';
    }
}
