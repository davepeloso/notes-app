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

    /**
     * Build inline CSS variables for a Filament badge from a hex color.
     * Produces --color-500 (base), --color-600 (darker ring), and text contrast vars.
     */
    public static function badgeStyleFromHex(?string $hex): string
    {
        $hex = $hex ?: '#9ca3af'; // fallback gray-400

        // Normalize e.g. #abc -> #aabbcc
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        // Parse RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Compute a lighter shade (approx `400`) by mixing with white (~40% white, 60% color)
        $lr = min(255, (int) round($r * 0.6 + 255 * 0.4));
        $lg = min(255, (int) round($g * 0.6 + 255 * 0.4));
        $lb = min(255, (int) round($b * 0.6 + 255 * 0.4));

        // Compute a darker shade for ring/background 600 by mixing with black (approx 25%)
        $dr = max(0, (int) floor($r * 0.75));
        $dg = max(0, (int) floor($g * 0.75));
        $db = max(0, (int) floor($b * 0.75));

        $hex400 = sprintf('#%02x%02x%02x', $lr, $lg, $lb);
        $hex500 = sprintf('#%02x%02x%02x', $r, $g, $b);
        $hex600 = sprintf('#%02x%02x%02x', $dr, $dg, $db);

        // Determine text contrast using luminance
        $lum = 0.2126 * ($r / 255) + 0.7152 * ($g / 255) + 0.0722 * ($b / 255);
        $text = ($lum > 0.6) ? '#1f1300' : '#ffffff';
        $darkText = '#ffffff';

        // Also force the background to use the vivid 500 shade so it doesn't wash out to white
        return "--color-400: {$hex400}; --color-500: {$hex500}; --color-600: {$hex600}; --text: {$text}; --dark-text: {$darkText}; background-color: var(--color-500);";
    }
}
