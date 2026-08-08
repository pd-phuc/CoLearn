<?php

namespace App\Models;

use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['section_id', 'title', 'slug', 'type', 'video_url', 'document_path', 'content', 'duration', 'is_free_preview', 'sort_order'])]
class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory, HasUlids;

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'is_free_preview' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }
}
