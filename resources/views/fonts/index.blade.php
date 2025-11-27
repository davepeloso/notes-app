<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Font Preview - NoteApp</title>
    
    <!-- Local Fonts -->
    <link rel="stylesheet" href="/fonts/google/google/wolf/stylesheet.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-preview-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .font-preview-card:hover {
            transform: translateY(-2px);
            border-color: rgb(251 191 36);
            box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1);
        }
        .weight-sample {
            transition: all 0.2s ease;
        }
        .weight-sample:hover {
            background-color: rgb(254 240 138);
            transform: scale(1.02);
        }
        .dark .weight-sample:hover {
            background-color: rgb(146 64 14);
        }
        
        /* Dynamic font families will be applied via inline styles where needed */
    </style>
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
                    <a href="/fonts" class="text-amber-600 dark:text-amber-500 hover:text-amber-700 dark:hover:text-amber-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Font Preview
                    </a>
                    <a href="/admin" class="text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        My Notes
                    </a>
                    <a href="/admin/notes/create" class="bg-amber-600 dark:bg-amber-500 text-white hover:bg-amber-700 dark:hover:bg-amber-600 px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                        Create Note
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-gradient-to-br from-amber-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Font Preview Gallery
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Explore your locally installed fonts with different weights and styles. Perfect for finding the right typography for your next note.
                </p>
            </div>
        </div>
    </div>

    <!-- Font Previews -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="space-y-16">
            @foreach($fonts as $fontName => $fontData)
                <div class="font-preview-card bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" style="font-family: '{{ $fontName }}', sans-serif;">
                            {{ $fontName }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ count($fontData['weights']) }} weights • {{ implode(', ', $fontData['styles']) }} styles
                        </p>
                    </div>

                    <!-- Sample Text -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Sample Text</h3>
                        <p class="text-xl leading-relaxed text-gray-700 dark:text-gray-300" style="font-family: '{{ $fontName }}', sans-serif; font-weight: 400;">
                            {{ $fontData['sample'] }}
                        </p>
                    </div>

                    <!-- Weight Variations -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Weight Variations</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($fontData['weights'] as $weightName => $weightValue)
                                @if(in_array('normal', $fontData['styles']))
                                    <div class="weight-sample p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $weightName }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-500">{{ $weightValue }}</span>
                                        </div>
                                        <p class="text-lg" style="font-family: '{{ $fontName }}', sans-serif; font-weight: {{ $weightValue }};">
                                            The quick brown fox jumps over the lazy dog.
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Italic Variations (if available) -->
                    @if(in_array('italic', $fontData['styles']))
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Italic Variations</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($fontData['weights'] as $weightName => $weightValue)
                                    <div class="weight-sample p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $weightName }} Italic</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-500">{{ $weightValue }}</span>
                                        </div>
                                        <p class="text-lg italic" style="font-family: '{{ $fontName }}', sans-serif; font-weight: {{ $weightValue }}; font-style: italic;">
                                            The quick brown fox jumps over the lazy dog.
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Character Set -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Character Set</h3>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300" style="font-family: '{{ $fontName }}', sans-serif; font-weight: 400;">
                                ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>
                                abcdefghijklmnopqrstuvwxyz<br>
                                0123456789<br>
                                !@#$%^&*()_+-=[]{}|;':",./<>?
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} NoteApp. Built with Laravel & Filament.</p>
                <p class="mt-2 text-sm">Font Preview Gallery • Explore typography possibilities</p>
            </div>
        </div>
    </footer>
</body>
</html>
