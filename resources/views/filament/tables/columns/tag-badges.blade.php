@php
    /** @var \App\Models\Note $record */
    $tags = $record->relationLoaded('tags') ? $record->tags : $record->tags()->get();
    $max = 3;
    $shown = $tags->take($max);
    $remaining = max(0, $tags->count() - $shown->count());
@endphp

<div class="inline-flex flex-wrap gap-1">
    @foreach ($shown as $tag)
        <span
            class="fi-badge fi-color fi-bg-color-500 fi-size-sm"
            style="{{ \App\Models\Tag::badgeStyleFromHex($tag->color) }}"
        >
            {{ $tag->name }}
        </span>
    @endforeach

    @if ($remaining > 0)
        <span class="fi-badge fi-size-sm">+{{ $remaining }}</span>
    @endif
</div>
