<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
