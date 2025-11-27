<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NoteApp - Your Personal Note-Taking Application</title>
    
    <!-- Local Fonts -->
    <link rel="stylesheet" href="/fonts/google/google/wolf/stylesheet.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Dark mode initialization
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="h-full antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center group">
                        <svg class="w-8 h-8 text-amber-600 dark:text-amber-500 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">NoteApp</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Home
                    </a>
                    <a href="/fonts" class="text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Font Preview
                    </a>
                    <a href="/admin" class="text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        My Notes
                    </a>
                    <a href="/admin/notes/create" class="bg-amber-600 dark:bg-amber-500 text-white hover:bg-amber-700 dark:hover:bg-amber-600 px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                        Create Note
                    </a>
                    <button id="theme-toggle" class="p-2 text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-500 rounded-md transition-colors">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-amber-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    Welcome to NoteApp
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    Your personal space for capturing ideas, organizing thoughts, and managing notes with powerful Markdown and PHP code editors.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="/admin/notes/create" class="bg-amber-600 dark:bg-amber-500 text-white hover:bg-amber-700 dark:hover:bg-amber-600 px-8 py-3 rounded-lg text-lg font-medium transition-colors shadow-lg hover:shadow-xl">
                        Create Your First Note
                    </a>
                    <a href="/admin" class="bg-white dark:bg-gray-800 text-amber-600 dark:text-amber-500 hover:bg-gray-50 dark:hover:bg-gray-700 px-8 py-3 rounded-lg text-lg font-medium transition-colors border-2 border-amber-600 dark:border-amber-500">
                        View All Notes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-100 mb-12">Powerful Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1: Markdown -->
            <a href="/admin/notes/create" class="group bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-800 hover:border-amber-500 dark:hover:border-amber-500">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Markdown Editor</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-3">
                    Write notes using Markdown with live preview, syntax highlighting, and GitHub Flavored Markdown support.
                </p>
                <span class="text-amber-600 dark:text-amber-500 text-sm font-medium group-hover:underline">Try Markdown Editor →</span>
            </a>

            <!-- Feature 2: PHP Editor -->
            <a href="/admin/notes/create" class="group bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-800 hover:border-amber-500 dark:hover:border-amber-500">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">PHP Monaco Editor</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-3">
                    Store and edit PHP code snippets with Monaco Editor, featuring IntelliSense, syntax highlighting, and code folding.
                </p>
                <span class="text-amber-600 dark:text-amber-500 text-sm font-medium group-hover:underline">Try PHP Editor →</span>
            </a>

            <!-- Feature 3: Search -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-800">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Global Search</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-3">
                    Search across all notes with Cmd/Ctrl+K. Find content in titles, markdown, and code instantly.
                </p>
                <span class="text-gray-500 dark:text-gray-600 text-sm font-medium">Press Cmd/Ctrl+K</span>
            </div>

            <!-- Feature 4: Recent Notes -->
            <a href="/admin" class="group bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-800 hover:border-amber-500 dark:hover:border-amber-500">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Recent Notes Widget</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-3">
                    Quick access to your 5 most recently updated notes right from the dashboard.
                </p>
                <span class="text-amber-600 dark:text-amber-500 text-sm font-medium group-hover:underline">View Dashboard →</span>
            </a>

            <!-- Feature 5: Copy to Clipboard -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-800">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Copy Code Blocks</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Hover over any code block in your markdown preview to copy it to clipboard with one click.
                </p>
            </div>

            <!-- Feature 6: Dark Mode -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-800">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Dark Mode Support</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Full dark mode support across all features for comfortable note-taking day or night.
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Start Section -->
    <div class="bg-gray-100 dark:bg-gray-900 py-16 border-y border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-100 mb-12">Quick Start</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-amber-600 dark:bg-amber-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold mx-auto mb-4 shadow-lg">
                        1
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Create a Note</h3>
                    <p class="text-gray-600 dark:text-gray-400">Click "Create Note" to start your first note</p>
                </div>
                <div class="text-center">
                    <div class="bg-amber-600 dark:bg-amber-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold mx-auto mb-4 shadow-lg">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Choose Your Editor</h3>
                    <p class="text-gray-600 dark:text-gray-400">Use Markdown tab or PHP Code tab based on your needs</p>
                </div>
                <div class="text-center">
                    <div class="bg-amber-600 dark:bg-amber-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold mx-auto mb-4 shadow-lg">
                        3
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Access Anytime</h3>
                    <p class="text-gray-600 dark:text-gray-400">View and edit your notes from anywhere</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-white dark:bg-gray-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ready to get started?</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">Create your first note and experience the power of our editors.</p>
            <div class="flex gap-4 justify-center">
                <a href="/admin/notes/create" class="bg-amber-600 dark:bg-amber-500 text-white hover:bg-amber-700 dark:hover:bg-amber-600 px-8 py-3 rounded-lg text-lg font-medium transition-colors shadow-lg hover:shadow-xl">
                    Get Started
                </a>
                <a href="/admin" class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-700 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                    View All Notes
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} NoteApp. Built with Laravel & Filament.</p>
                <p class="mt-2 text-sm">Featuring Monaco Editor, Markdown Live Preview & Dark Mode</p>
            </div>
        </div>
    </footer>

    <!-- Dark Mode Toggle Script -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Show correct icon on load
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // Toggle dark mode
            if (localStorage.theme === 'dark') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });
    </script>
</body>
</html>
