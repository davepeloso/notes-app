@php
    $statePath = $getStatePath();
    $state = $getState();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: @js($state ?? ''),
            activeView: 'split',
            isCopied: false,
            init() {
                this.$nextTick(() => {
                    this.updatePreview();
                    this.autoGrow();
                });
                this.$watch('state', () => {
                    this.autoGrow();
                    this.updatePreview();
                });
            },
            updatePreview() {
                if (typeof marked === 'undefined') return;
                const markdown = this.state || '';
                const html = marked.parse(markdown);
                this.$refs.preview.innerHTML = html;
                this.addCopyButtons();
            },
            addCopyButtons() {
                const codeBlocks = this.$refs.preview?.querySelectorAll('pre code') ?? [];
                codeBlocks.forEach((block) => {
                    const pre = block.parentElement;
                    if (!pre || pre.querySelector('.copy-button')) return;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'copy-button absolute top-2 right-2 px-3 py-1 text-xs bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors';
                    button.textContent = 'Copy';
                    button.onclick = () => {
                        navigator.clipboard?.writeText(block.textContent ?? '').then(() => {
                            button.textContent = 'Copied!';
                            button.classList.add('bg-green-600');
                            button.classList.remove('bg-gray-700');
                            setTimeout(() => {
                                button.textContent = 'Copy';
                                button.classList.remove('bg-green-600');
                                button.classList.add('bg-gray-700');
                            }, 2000);
                        });
                    };

                    pre.style.position = 'relative';
                    pre.appendChild(button);
                });
            },
            wrapSelection(startTag, endTag = '', placeholder = 'text') {
                const textarea = this.$refs.textarea;
                if (!textarea) return;
                const value = this.state || '';
                const start = textarea.selectionStart ?? 0;
                const end = textarea.selectionEnd ?? start;
                const selected = value.slice(start, end) || placeholder;
                this.state = value.slice(0, start) + startTag + selected + endTag + value.slice(end);
                this.$nextTick(() => {
                    textarea.focus();
                    textarea.selectionStart = start + startTag.length;
                    textarea.selectionEnd = start + startTag.length + selected.length;
                });
            },
            insertPrefix(prefix) {
                const textarea = this.$refs.textarea;
                if (!textarea) return;
                const value = this.state || '';
                const start = textarea.selectionStart ?? 0;
                const end = textarea.selectionEnd ?? start;
                if (start === end) {
                    const lineStart = value.lastIndexOf('\n', start - 1) + 1;
                    this.state = value.slice(0, lineStart) + prefix + value.slice(lineStart);
                    this.$nextTick(() => {
                        const offset = prefix.length;
                        textarea.selectionStart = start + offset;
                        textarea.selectionEnd = start + offset;
                    });
                    return;
                }

                const selection = value.slice(start, end);
                const formatted = selection
                    .split('\n')
                    .map((line) => (line ? prefix + line : prefix.trimEnd()))
                    .join('\n');

                this.state = value.slice(0, start) + formatted + value.slice(end);
                this.$nextTick(() => {
                    textarea.selectionStart = start;
                    textarea.selectionEnd = start + formatted.length;
                });
            },
            insertHeading(level = 2) {
                this.insertPrefix('#'.repeat(level) + ' ');
            },
            insertLink() {
                const url = prompt('Enter the URL');
                if (!url) return;
                this.wrapSelection('[', `](${url})`, 'Link text');
            },
            insertImage() {
                const url = prompt('Enter the image URL');
                if (!url) return;
                this.wrapSelection('![', `](${url})`, 'Alt text');
            },
            insertCodeBlock() {
                this.wrapSelection('\n```\n', '\n```\n', 'code');
            },
            insertDivider() {
                this.wrapSelection('\n\n---\n\n', '', '');
            },
            insertTab() {
                const textarea = this.$refs.textarea;
                if (!textarea) return;
                const value = this.state || '';
                const start = textarea.selectionStart ?? 0;
                const end = textarea.selectionEnd ?? start;
                this.state = value.slice(0, start) + '    ' + value.slice(end);
                this.$nextTick(() => {
                    textarea.selectionStart = start + 4;
                    textarea.selectionEnd = start + 4;
                });
            },
            autoGrow() {
                const textarea = this.$refs.textarea;
                if (!textarea) return;
                textarea.style.height = 'auto';
                textarea.style.height = Math.max(280, textarea.scrollHeight) + 'px';
            },
            setView(view) {
                this.activeView = view;
            },
            isView(view) {
                return this.activeView === view;
            },
            copyMarkdown() {
                navigator.clipboard?.writeText(this.state || '').then(() => {
                    this.isCopied = true;
                    setTimeout(() => (this.isCopied = false), 2000);
                });
            },
            wordCount() {
                if (!this.state?.trim()) return 0;
                return this.state.trim().split(/\s+/).length;
            },
            readingTime() {
                const words = this.wordCount();
                return Math.max(1, Math.round(words / 200));
            }
        }"
        x-init="init()"
        wire:ignore
        class="markdown-editor-container"
    >
        <div class="space-y-4 markdown-editor-shell" x-cloak>
            <!-- Toolbar -->
            <div class="markdown-editor-toolbar flex flex-wrap items-center gap-3 justify-between border border-indigo-100/60 dark:border-indigo-500/30 rounded-2xl px-4 py-3 bg-white/80 dark:bg-indigo-950/60 shadow-sm backdrop-blur-sm">
                <div class="flex flex-wrap items-center gap-1">
                    <span class="toolbar-label text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-200 mr-2">Formatting</span>
                    <button type="button" @click="wrapSelection('**','**','Bold text')" class="markdown-toolbar-button">B</button>
                    <button type="button" @click="wrapSelection('*','*','Italic text')" class="markdown-toolbar-button italic">I</button>
                    <button type="button" @click="insertHeading(2)" class="markdown-toolbar-button">H2</button>
                    <button type="button" @click="wrapSelection('`','`','code')" class="markdown-toolbar-button">`</button>
                    <button type="button" @click="insertCodeBlock()" class="markdown-toolbar-button">&lt;/&gt;</button>
                    <button type="button" @click="insertPrefix('> ')" class="markdown-toolbar-button">Quote</button>
                    <button type="button" @click="insertPrefix('- ')" class="markdown-toolbar-button">List</button>
                    <button type="button" @click="insertPrefix('1. ')" class="markdown-toolbar-button">1.</button>
                    <button type="button" @click="insertPrefix('- [ ] ')" class="markdown-toolbar-button">Todo</button>
                    <button type="button" @click="insertLink()" class="markdown-toolbar-button">Link</button>
                    <button type="button" @click="insertImage()" class="markdown-toolbar-button">Img</button>
                    <button type="button" @click="insertDivider()" class="markdown-toolbar-button">---</button>
                </div>

                <div class="flex items-center gap-2">
                    <div class="inline-flex rounded-full shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-gray-900 markdown-view-toggle-group" role="group">
                        <button
                            type="button"
                            @click="setView('editor')"
                            :class="[
                                'markdown-view-toggle',
                                isView('editor') ? 'is-active' : ''
                            ]"
                        >Editor</button>
                        <button
                            type="button"
                            @click="setView('split')"
                            :class="[
                                'markdown-view-toggle is-divider',
                                isView('split') ? 'is-active' : ''
                            ]"
                        >Split</button>
                        <button
                            type="button"
                            @click="setView('preview')"
                            :class="[
                                'markdown-view-toggle',
                                isView('preview') ? 'is-active' : ''
                            ]"
                        >Preview</button>
                    </div>

                    <button type="button" @click="copyMarkdown()" class="markdown-copy-btn px-3 py-1.5 text-xs font-semibold rounded-full border border-indigo-200 dark:border-indigo-500 text-indigo-700 dark:text-indigo-200 bg-white/90 dark:bg-indigo-900 hover:bg-indigo-50 dark:hover:bg-indigo-800 transition shadow-sm" x-text="isCopied ? 'Copied' : 'Copy MD'"></button>
                </div>
            </div>

            <!-- Split Screen Layout -->
            <div class="grid gap-4" :class="activeView === 'split' ? 'grid-cols-2' : 'grid-cols-1'">
                <!-- Editor Panel -->
                <div class="flex flex-col border border-gray-300 dark:border-gray-600 rounded-2xl overflow-hidden shadow-sm markdown-editor-panel editor-panel" x-show="activeView !== 'preview'" x-transition.opacity>
                    <div class="panel-heading bg-gray-100 dark:bg-gray-700/70 px-4 py-2 border-b border-gray-300 dark:border-gray-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Markdown Editor</span>
                    </div>
                    <textarea
                        x-ref="textarea"
                        x-model="state"
                        x-on:input.debounce.500ms="$wire.set('{{ $statePath }}', state)"
                        @keydown.tab.prevent="insertTab()"
                        class="w-full p-4 font-mono text-sm markdown-textarea bg-white/90 dark:bg-transparent text-gray-900 dark:text-gray-100 focus:outline-none resize-none border-0 focus:ring-0 placeholder:text-gray-400"
                        style="min-height: 320px; width: 100%;"
                        placeholder=""
                        spellcheck="false"
                    ></textarea>

                    <div class="flex flex-wrap items-center justify-between gap-2 px-4 py-2 text-xs border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 markdown-stats-bar">
                        <div class="space-x-4 text-gray-600 dark:text-gray-300">
                            <span><strong x-text="wordCount()"></strong> words</span>
                            <span><strong x-text="state?.length || 0"></strong> chars</span>
                        </div>
                        <span class="text-gray-500 dark:text-gray-400">~<span x-text="readingTime()"></span> min read</span>
                    </div>
                </div>

                <!-- Preview Panel -->
                <div class="border border-gray-300 dark:border-gray-600 rounded-2xl overflow-hidden shadow-sm markdown-editor-panel preview-panel" x-show="activeView !== 'editor'" x-transition.opacity>
                    <div class="panel-heading bg-gray-100/80 dark:bg-gray-700/70 px-4 py-2 border-b border-gray-300 dark:border-gray-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Live Preview</span>
                    </div>
                    <div
                        x-ref="preview"
                        class="w-full h-[500px] p-4 overflow-y-auto bg-white/90 dark:bg-transparent prose prose-sm dark:prose-invert max-w-none markdown-preview"
                        style="min-height: 320px;"
                    ></div>
                </div>
            </div>
        </div>
</x-dynamic-component>

@once
    @push('styles')
        <style>
            .markdown-editor-shell {
                font-family: var(--font-sans, 'Instrument Sans', ui-sans-serif, system-ui, sans-serif);
                gap: 1rem;
            }

            .markdown-editor-toolbar {
                border-radius: 1.5rem;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(6px);
            }

            .markdown-toolbar-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.3rem 0.7rem;
                border: 1px solid rgba(99, 102, 241, 0.2);
                border-radius: 0.45rem;
                font-size: 0.75rem;
                font-weight: 600;
                color: #1f2937;
                background-color: #ffffff;
                transition: all 0.15s ease;
            }

            .dark .markdown-toolbar-button {
                color: #f8fafc;
                background-color: rgba(20, 20, 32, 0.9);
                border-color: rgba(148, 163, 184, 0.4);
            }

            .markdown-toolbar-button:hover,
            .markdown-toolbar-button:focus {
                border-color: rgba(99, 102, 241, 0.6);
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
            }

            .markdown-view-toggle-group {
                background: rgba(255, 255, 255, 0.8);
            }

            .markdown-view-toggle {
                border: none;
                background: transparent;
                padding: 0.4rem 0.9rem;
                font-size: 0.75rem;
                font-weight: 600;
                color: #475569;
                transition: all 0.15s ease;
            }

            .markdown-view-toggle.is-divider {
                border-left: 1px solid rgba(148, 163, 184, 0.4);
                border-right: 1px solid rgba(148, 163, 184, 0.4);
            }

            .markdown-view-toggle.is-active {
                background: #4f46e5;
                color: #ffffff;
                border-radius: 9999px;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            }

            .markdown-copy-btn {
                letter-spacing: 0.04em;
            }

            .markdown-editor-panel {
                backdrop-filter: blur(4px);
            }

            .markdown-editor-panel.editor-panel {
                background-color: rgba(27, 19, 35, 0.5);
            }

            .markdown-editor-panel.preview-panel {
                background-color: rgba(0, 196, 255, 0.09);
            }

            .markdown-textarea,
            .markdown-preview {
                backdrop-filter: blur(2px);
            }

            .markdown-stats-bar {
                background: rgba(248, 250, 252, 0.85);
            }

            .dark .markdown-editor-toolbar {
                background: rgba(8, 7, 16, 0.85);
            }

            .dark .markdown-view-toggle-group {
                background: rgba(15, 23, 42, 0.85);
            }

            .dark .markdown-stats-bar {
                background: rgba(15, 23, 42, 0.65);
            }
        </style>
    @endpush
@endonce

@once
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/highlight.min.js"></script>
    <script>
        if (typeof marked !== 'undefined') {
            marked.setOptions({
                highlight: function(code, lang) {
                    if (lang && hljs.getLanguage(lang)) {
                        try {
                            return hljs.highlight(code, { language: lang }).value;
                        } catch (err) {}
                    }
                    return hljs.highlightAuto(code).value;
                },
                breaks: true,
                gfm: true
            });
        }
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/styles/github-dark.min.css">
    <style>
        .markdown-editor-container pre {
            position: relative;
        }
        .markdown-editor-container .copy-button {
            opacity: 0;
            transition: opacity 0.2s;
        }
        .markdown-editor-container pre:hover .copy-button {
            opacity: 1;
        }
        .markdown-editor-container textarea {
            tab-size: 4;
            -moz-tab-size: 4;
        }
    </style>
    @endpush
@endonce
