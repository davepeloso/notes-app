<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <div class="flex flex-wrap gap-3">
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
            <button onclick="window.dispatchEvent(new KeyboardEvent('keydown', { key: 'k', metaKey: true, bubbles: true }))"
                    class="inline-flex items-center rounded-full border border-green-400/70 bg-green-500/5 px-4 py-1.5 text-sm font-medium text-green-200 hover:bg-green-500/15 hover:text-green-100 transition-colors">
                Search Notes (Cmd+K)
            </button>
        </div>

        <!-- Stats Section -->
        <div class="mt-6">
            @php
                $totalNotes = \App\Models\Note::count();
                $todayNotes = \App\Models\Note::whereDate('created_at', today())->count();
                $updatedToday = \App\Models\Note::whereDate('updated_at', today())->count();
            @endphp

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full text-sm">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        <tr>
                            <th scope="row" class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">
                                Total Notes
                            </th>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                    {{ $totalNotes }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">
                                Created Today
                            </th>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    {{ $todayNotes }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">
                                Updated Today
                            </th>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                                    {{ $updatedToday }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
