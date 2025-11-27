@php
    $statePath = $getStatePath();
    $state = $getState();
    $record = $getRecord();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: @js($state ?? ''),
        }"
        wire:ignore
        class="php-editor flex flex-col border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
    >
        <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 border-b border-gray-300 dark:border-gray-600 flex items-center justify-between gap-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PHP Code Editor</span>

            @if ($record)
                <a
                    href="{{ route('notes.monaco.edit', $record) }}"
                    target="_blank"
                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-gray-900"
                >
                    Code
                </a>
            @endif
        </div>
        <textarea
            x-model="state"
            x-on:input.debounce.500ms="$wire.set('{{ $statePath }}', state)"
            class="w-full p-4 font-mono text-sm bg-gray-900 text-gray-100 focus:outline-none resize-none border-0 focus:ring-0"
            style="height: 70vh; width: 100%;"
            placeholder="Enter your PHP code here..."
            spellcheck="false"
        ></textarea>
    </div>
</x-dynamic-component>

@once
    @push('styles')
    <style>
        /* PHP Editor Styling */
        .php-editor textarea {
            tab-size: 4;
            -moz-tab-size: 4;
        }
    </style>
    @endpush
@endonce

