<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <div
            id="quick-actions-shortcuts"
            data-note-create-url="{{ route('filament.admin.resources.notes.create') }}"
            data-project-create-url="{{ route('filament.admin.resources.projects.create') }}"
            data-tag-create-url="{{ route('filament.admin.resources.tags.create') }}"
            data-notes-index-url="{{ route('filament.admin.resources.notes.index') }}"
            class="flex flex-wrap gap-3"
        >
            <!-- New Note Button -->
            <a href="{{ route('filament.admin.resources.notes.create') }}"
            class="inline-flex items-center rounded-full border border-amber-500/70 bg-amber-500/10 px-4 py-1.5 text-sm font-medium text-amber-300 hover:bg-amber-500/20 hover:text-amber-100 transition-colors">
                New Notes
            </a>

            <!-- All Notes Button -->
            <a href="{{ route('filament.admin.resources.notes.index') }}"
            class="inline-flex items-center rounded-full border border-blue-400/70 bg-blue-500/5 px-4 py-1.5 text-sm font-medium text-blue-200 hover:bg-blue-500/15 hover:text-blue-100 transition-colors">
                All Notes
            </a>

            <!-- Search Notes Button -->
            <a href="{{ route('filament.admin.resources.notes.index') }}"
               class="inline-flex items-center rounded-full border border-green-400/70 bg-green-500/5 px-4 py-1.5 text-sm font-medium text-green-200 hover:bg-green-500/15 hover:text-green-100 transition-colors">
                Search Notes
            </a>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
