@php
    $statePath = $getStatePath();
    $id = 'mde-' . str_replace('.', '-', $statePath);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        wire:ignore
        x-data="{
            state: @js($getState()),
            init() {
                // Ensure EasyMDE loads after the modal is fully rendered
                setTimeout(() => {
                    if (window.easyMdeInstances && window.easyMdeInstances['{{ $id }}']) {
                        return;
                    }

                    window.easyMdeInstances = window.easyMdeInstances || {};

                    const easyMDE = new EasyMDE({
                        element: document.getElementById('{{ $id }}'),
                        initialValue: this.state,
                        spellChecker: false,
                        // Full toolbar, customize as needed from GEMINI.md
                        toolbar: [
                            'bold', 'italic', 'heading', '|',
                            'quote', 'unordered-list', 'ordered-list', '|',
                            'link', 'image', 'code', 'table', '|',
                            'preview', 'side-by-side', 'fullscreen', '|',
                            'guide'
                        ],
                        autoDownloadFontAwesome: false, // Recommended for performance
                        // Disable live preview in modals as per requirements
                        previewRender: (plainText) => {
                           return '<div class="p-4">Preview is disabled in modals.</div>';
                        },
                    });

                    window.easyMdeInstances['{{ $id }}'] = easyMDE;

                    easyMDE.codemirror.on('change', () => {
                        this.state = easyMDE.value();
                    });

                    this.$watch('state', _.debounce((newState) => {
                        $wire.set('{{ $statePath }}', newState, true);
                    }, 300));

                }, 150);
            }
        }"
        x-init="init()"
        class="filament-modal-markdown-editor"
    >
        {{-- Add the required styling from GEMINI.md --}}
        <div class="border border-neutral-700 bg-neutral-900 rounded-xl shadow-inner p-2">
            <textarea id="{{ $id }}" class="hidden"></textarea>
        </div>
    </div>
</x-dynamic-component>

@once
    @push('scripts')
        {{-- Load EasyMDE assets --}}
        <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
        <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
        {{-- Load lodash for debouncing --}}
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    @endpush
@endonce
