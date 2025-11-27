<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'color',
        'is_flag',
    ];

    protected $casts = [
        'is_flag' => 'boolean',
    ];

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class)->withTimestamps();
    }

    // Scopes
    public function scopeFlags($query)
    {
        return $query->where('is_flag', true);
    }

    public function scopeRegularTags($query)
    {
        return $query->where('is_flag', false);
    }
}
