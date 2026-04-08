<?php

namespace App\Services;

class ChordProParser
{
    public function parse(string $chordPro): array
    {
        $lines = preg_split("/\r\n|\n|\r/", $chordPro) ?: [];
        $sections = [];
        $current = null;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                if ($current && end($current['lines']) !== '') {
                    $current['lines'][] = '';
                }

                continue;
            }

            if (preg_match('/^\{(?:soc|start_of_chorus)\}$/i', $trimmed)) {
                if ($current) {
                    $sections[] = $current;
                }

                $current = ['name' => 'Chorus', 'lines' => []];
                continue;
            }

            if (preg_match('/^\{(?:eoc|end_of_chorus)\}$/i', $trimmed)) {
                if ($current) {
                    $sections[] = $current;
                    $current = null;
                }

                continue;
            }

            if (preg_match('/^\{(?:sot|start_of_tab)\}$/i', $trimmed)) {
                if ($current) {
                    $sections[] = $current;
                }

                $current = ['name' => 'Tab', 'lines' => []];
                continue;
            }

            if (preg_match('/^\{(?:eot|end_of_tab)\}$/i', $trimmed)) {
                if ($current) {
                    $sections[] = $current;
                    $current = null;
                }

                continue;
            }

            if (preg_match('/^\{(?:comment|c|title|t)\s*:\s*(.+)\}$/i', $trimmed, $matches)) {
                if ($current) {
                    $sections[] = $current;
                }

                $current = ['name' => trim($matches[1]), 'lines' => []];
                continue;
            }

            if (preg_match('/^\{.*\}$/', $trimmed)) {
                continue;
            }

            if (! $current) {
                $current = ['name' => 'Song', 'lines' => []];
            }

            $current['lines'][] = $line;
        }

        if ($current) {
            $sections[] = $current;
        }

        return [
            'sections' => array_values(array_filter($sections, static fn (array $section) => count($section['lines']) > 0)),
        ];
    }
}
