<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Note extends Model
{
    protected $fillable = [
        'title',
        'type',
        'content',
        'code_content',
        'project_id',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function flags(): BelongsToMany
    {
        return $this->tags()->where('is_flag', true);
    }

    // Scopes
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWithTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    public function scopeWithFlag($query, $flagId)
    {
        return $query->whereHas('flags', function ($q) use ($flagId) {
            $q->where('tags.id', $flagId);
        });
    }

    public function scopeInProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }
}
