<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'artist',
        'default_key',
        'bpm',
        'time_signature',
        'structure',
        'chordpro',
        'chart_path',
        'notes',
    ];

    protected $casts = [
        'structure' => 'array',
        'bpm' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function liveSets(): BelongsToMany
    {
        return $this->belongsToMany(LiveSet::class)
            ->withPivot('position');
    }
}
