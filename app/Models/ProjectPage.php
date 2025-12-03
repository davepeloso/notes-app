<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProjectPage extends Model
{
    protected $fillable = [
        'project_id',
        'slug',
        'is_published',
        'custom_content',
        'meta_data',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'meta_data' => 'array',
    ];

    /**
     * Relationship to Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Generate a unique slug from project name
     */
    public static function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the URL for this project page
     */
    public function getUrlAttribute(): string
    {
        return route('project.show', $this->slug);
    }

    /**
     * Get the content to display (custom or from project README)
     */
    public function getDisplayContentAttribute(): ?string
    {
        if ($this->custom_content) {
            return $this->custom_content;
        }

        return $this->project->content ?? $this->project->description;
    }
}
