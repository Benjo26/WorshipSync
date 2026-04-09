<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->longText('chordpro')->nullable()->after('structure');
        });

        DB::table('songs')
            ->select(['id', 'chart_path'])
            ->orderBy('id')
            ->chunkById(100, function ($songs): void {
                foreach ($songs as $song) {
                    if (! $song->chart_path || ! Storage::disk('local')->exists($song->chart_path)) {
                        continue;
                    }

                    DB::table('songs')
                        ->where('id', $song->id)
                        ->update([
                            'chordpro' => Storage::disk('local')->get($song->chart_path),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('chordpro');
        });
    }
};
