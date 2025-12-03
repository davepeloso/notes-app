<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom animations and styles inspired by the template */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease-out forwards;
        }

        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }

        .gradient-border {
            position: relative;
            background: linear-gradient(135deg, rgba(94, 163, 255, 0.1), rgba(107, 176, 255, 0.05));
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(94, 163, 255, 0.4), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gradient-border:hover::before {
            opacity: 1;
        }

        /* Markdown content styling */
        .prose {
            max-width: 65ch;
        }

        .prose h1 { @apply text-3xl font-bold text-white mt-8 mb-4; }
        .prose h2 { @apply text-2xl font-semibold text-white mt-6 mb-3; }
        .prose h3 { @apply text-xl font-medium text-white mt-4 mb-2; }
        .prose p { @apply text-slate-300 my-3 leading-relaxed; }
        .prose code { @apply bg-slate-800/50 px-2 py-1 rounded text-blue-300 text-sm; }
        .prose pre { @apply bg-slate-900/50 p-4 rounded-lg overflow-x-auto my-4 border border-slate-700/40; }
        .prose pre code { @apply bg-transparent p-0; }
        .prose ul { @apply list-disc list-inside text-slate-300 my-3 space-y-1; }
        .prose ol { @apply list-decimal list-inside text-slate-300 my-3 space-y-1; }
        .prose a { @apply text-blue-400 hover:text-blue-300 underline; }
        .prose blockquote { @apply border-l-4 border-slate-600 pl-4 italic text-slate-400 my-4; }

        /* Tag styles */
        .tag {
            @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-800/50 text-slate-300 border border-slate-700/40;
        }
    </style>
</head>
<body class="min-h-screen w-full relative overflow-x-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    
    <!-- Decorative gradient overlay -->
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_left,_rgba(63,41,121,0.4)_0%,_transparent_50%)]"></div>
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_bottom_right,_rgba(94,163,255,0.2)_0%,_transparent_50%)]"></div>

    <main class="max-w-6xl mx-auto px-6 py-16">
        
        <!-- Back button -->
        <div class="mb-8 fade-up stagger-1">
            <a href="{{ url('/admin/projects') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Projects
            </a>
        </div>

        <!-- Header Section -->
        <header class="mb-12 fade-up stagger-2">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ $project->name }}
            </h1>
            
            @if($project->description)
                <p class="text-xl text-slate-300 max-w-3xl">
                    {{ $project->description }}
                </p>
            @endif

            <!-- Tags -->
            @if($tags && $tags->count() > 0)
                <div class="flex flex-wrap gap-2 mt-6">
                    @foreach($tags as $tag)
                        <span class="tag" style="border-color: {{ $tag->color }}20; background-color: {{ $tag->color }}10;">
                            <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $tag->color }};"></span>
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </header>

        <!-- Project Metadata Card -->
        <article class="gradient-border rounded-2xl p-8 md:p-12 mb-8 fade-up stagger-3 backdrop-blur-sm">
            <div class="grid md:grid-cols-3 gap-8">
                
                <!-- Icon/Visual -->
                <div class="flex justify-center md:justify-start items-center">
                    <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="44" stroke="#4ea3ff" stroke-width="1.6" opacity="0.08" />
                        <path d="M40 60h40M60 40v40" stroke="#86b9ff" stroke-width="2.2" stroke-linecap="round" />
                        <circle cx="60" cy="60" r="8" fill="#5fb3ff" opacity="0.6" />
                    </svg>
                </div>

                <!-- Project Info -->
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Project ID</h3>
                        <p class="text-slate-200 font-mono text-sm">{{ $project->project_id ?? 'N/A' }}</p>
                    </div>

                    @if($project->context)
                        <div>
                            <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Context</h3>
                            <p class="text-slate-300 text-sm">{{ $project->context }}</p>
                        </div>
                    @endif

                    <div class="flex gap-4 pt-2">
                        <div>
                            <p class="text-xs text-slate-500">Created</p>
                            <p class="text-sm text-slate-300">{{ $project->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Updated</p>
                            <p class="text-sm text-slate-300">{{ $project->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Main Content -->
        @if($content)
            <article class="gradient-border rounded-2xl p-8 md:p-12 mb-8 fade-up stagger-4 backdrop-blur-sm">
                <div class="prose prose-invert max-w-none">
                    {!! \Illuminate\Support\Str::markdown($content) !!}
                </div>
            </article>
        @else
            <article class="gradient-border rounded-2xl p-8 md:p-12 mb-8 fade-up stagger-4 backdrop-blur-sm">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-slate-400 text-lg">No content available for this project yet.</p>
                    <p class="text-slate-500 text-sm mt-2">Add content in the admin panel to see it here.</p>
                </div>
            </article>
        @endif

        <!-- Footer Actions -->
        <div class="flex justify-between items-center mt-12 fade-up stagger-4">
            <a href="{{ url('/admin/projects/' . $project->id . '/edit') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-600 text-slate-200 hover:bg-slate-800/40 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Project
            </a>

            <div class="text-sm text-slate-500">
                Page URL: <code class="text-slate-400 font-mono">{{ $projectPage->slug }}</code>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-12 text-center text-slate-400 text-sm">
        © {{ date('Y') }} Project Organizer • Built with Laravel & Filament
    </footer>

</body>
</html>
