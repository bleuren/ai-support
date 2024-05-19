<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'question',
        'answer',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    protected static function booted()
    {
        static::saved(function ($question) {
            self::clearCache($question->team_id);
        });

        static::deleted(function ($question) {
            self::clearCache($question->team_id);
        });
    }

    protected static function clearCache($teamId)
    {
        Cache::forget("question_embeddings_{$teamId}");
    }
}
