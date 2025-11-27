<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monaco PHP Editor - {{ $note->title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Force dark mode for the Monaco pop-out page
        document.documentElement.classList.add('dark');
    </script>
</head>
<body class="h-full antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    <div class="flex flex-col h-screen">
        <header class="px-4 py-3 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-sm font-semibold text-gray-900 dark:text-gray-100">PHP Monaco Editor</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        Note: {{ $note->title }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url('/admin/notes/' . $note->id . '/edit') }}" class="px-3 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Back to Note
                    </a>
                    <button form="monaco-form" type="submit" class="px-4 py-1.5 text-xs font-semibold rounded-md bg-amber-600 hover:bg-amber-700 text-white shadow-sm">
                        Save
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <form id="monaco-form" method="POST" action="{{ route('notes.monaco.update', $note) }}" class="h-full">
                @csrf

                <textarea
                    id="code_content_field"
                    name="code_content"
                    class="hidden"
                >{{ old('code_content', $note->code_content) }}</textarea>

                <div id="monaco-container" class="w-full h-[calc(100vh-56px)]"></div>
            </form>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('monaco-container');
            const form = document.getElementById('monaco-form');
            const field = document.getElementById('code_content_field');

            if (!container || !field || !window.monaco || !window.monaco.editor) {
                return;
            }

            const initialValue = field.value || '';

            const editor = window.monaco.editor.create(container, {
                value: initialValue,
                language: 'php',
                theme: 'vs-dark',
                automaticLayout: true,
                minimap: { enabled: false },
                lineNumbers: 'on',
                folding: true,
            });

            if (form) {
                form.addEventListener('submit', function () {
                    field.value = editor.getValue();
                });
            }
        });
    </script>
</body>
</html>
