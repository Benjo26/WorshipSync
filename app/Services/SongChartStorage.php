<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SongChartStorage
{
    public function store(string $chart): string
    {
        $path = $this->path();

        Storage::disk('local')->put($path, $chart);

        return $path;
    }

    public function read(string $path): string
    {
        if (! Storage::disk('local')->exists($path)) {
            return '';
        }

        return Storage::disk('local')->get($path);
    }

    public function replace(string $path, string $chart): string
    {
        Storage::disk('local')->put($path, $chart);

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk('local')->delete($path);
    }

    private function path(): string
    {
        return 'songs/' . Uuid::uuid7()->toString() . '.chordpro';
    }
}
