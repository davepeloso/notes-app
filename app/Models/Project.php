<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'content',
        'context',
        'project_id',
    ];

    /**
     * Relationship to ProjectPage
     */
    public function page(): HasOne
    {
        return $this->hasOne(ProjectPage::class);
    }

    /**
     * Relationship to tags
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'note_tag', 'note_id', 'tag_id');
    }

    /**
     * Relationship to notes
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Check if this project has a published page
     */
    public function hasPublishedPage(): bool
    {
        return $this->page && $this->page->is_published;
    }

    /**
     * Get the page URL if it exists
     */
    public function getPageUrlAttribute(): ?string
    {
        return $this->page?->url;
    }
}
