
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Base font and link colors for dark background */
        html { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji"; color: #e5e7eb; }
        a { color: #60a5fa; }
        a:hover { color: #93c5fd; }

        /* Prevent long words / URLs from running off the page */
        .prose, .gradient-border, .content-wrap { overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word; }

        /* Animations */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; }
        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }
        .stagger-5 { animation-delay: 0.5s; opacity: 0; }

        /* Card with subtle gradient border */
        .gradient-border { position: relative; background: linear-gradient(135deg, rgba(94, 163, 255, 0.1), rgba(107, 176, 255, 0.05)); border: 1px solid rgba(148, 163, 184, 0.3); }
        .gradient-border::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px; background: linear-gradient(135deg, rgba(94, 163, 255, 0.4), transparent); opacity: 0; transition: opacity 0.3s; }

        /* Force headings to be light for contrast within cards */
        .gradient-border h1, .gradient-border h2, .gradient-border h3, .gradient-border h4, .gradient-border h5, .gradient-border h6 { color: #fff; }

        /* Markdown content styling (plain CSS instead of Tailwind @apply) */
        .prose { max-width: 65ch; }
        .prose h1 { font-size: 1.875rem; line-height: 2.25rem; font-weight: 700; color: #fff; margin-top: 2rem; margin-bottom: 1rem; }
        .prose h2 { font-size: 1.5rem; line-height: 2rem; font-weight: 600; color: #fff; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .prose h3 { font-size: 1.25rem; line-height: 1.75rem; font-weight: 500; color: #fff; margin-top: 1rem; margin-bottom: 0.5rem; }
        .prose p { color: #cbd5e1; margin: 0.75rem 0; line-height: 1.625; }
        .prose code { background: rgba(30,41,59,0.5); padding: 0.25rem 0.5rem; border-radius: 0.375rem; color: #93c5fd; font-size: 0.875rem; }
        .prose pre { background: rgba(2,6,23,0.5); padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin: 1rem 0; border: 1px solid rgba(51,65,85,0.4); }
        .prose pre code { background: transparent; padding: 0; }
        .prose ul { list-style: disc inside; color: #cbd5e1; margin: 0.75rem 0; }
        .prose ol { list-style: decimal inside; color: #cbd5e1; margin: 0.75rem 0; }
        .prose a { color: #60a5fa; text-decoration: underline; }
        .prose a:hover { color: #93c5fd; }
        .prose blockquote { border-left: 4px solid #475569; padding-left: 1rem; font-style: italic; color: #94a3b8; margin: 1rem 0; }

        /* Tag pill */
        .tag { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: rgba(30,41,59,0.5); color: #cbd5e1; border: 1px solid rgba(51,65,85,0.4); }
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

                    @if($project->description)
                        <div>
                            <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Description</h3>
                            <p class="text-slate-300 text-sm whitespace-pre-line">{{ $project->description }}</p>
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

        <!-- Project Notes -->
        @if($project->notes && $project->notes->count() > 0)
            <article class="gradient-border rounded-2xl p-8 md:p-12 mb-8 fade-up stagger-5 backdrop-blur-sm">
                <header class="mb-6">
                    <h2 class="text-2xl md:text-3xl font-semibold text-white">Project Notes</h2>
                    <p class="text-slate-400 text-sm mt-1">{{ $project->notes->count() }} note{{ $project->notes->count() === 1 ? '' : 's' }}</p>
                </header>

                <div class="space-y-8">
                    @foreach($project->notes as $note)
                        <section class="border border-slate-700/40 rounded-xl p-5 bg-slate-900/30">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-white">{{ $note->title }}</h3>
                                    <span class="text-xs px-2 py-0.5 rounded-full border border-slate-600/60 text-slate-300 uppercase tracking-wide">
                                        {{ ucfirst($note->type) }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500">Updated {{ $note->updated_at?->diffForHumans() }}</div>
                            </div>

                            @if($note->tags && $note->tags->count() > 0)
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($note->tags as $tag)
                                        <span class="tag" style="border-color: {{ $tag->color }}20; background-color: {{ $tag->color }}10;">
                                            <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $tag->color }};"></span>
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="prose prose-invert max-w-none mt-4">
                                @if(in_array($note->type, ['markdown', 'mixed']))
                                    @if(!empty($note->content))
                                        {!! \Illuminate\Support\Str::markdown($note->content) !!}
                                    @endif
                                @endif

                                @if(in_array($note->type, ['code', 'mixed']))
                                    @if(!empty($note->code_content))
                                        <pre><code class="language-php">{{ $note->code_content }}</code></pre>
                                    @endif
                                @endif
                            </div>
                        </section>
                    @endforeach
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
