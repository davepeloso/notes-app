// Import Monaco Editor core
import * as monaco from "monaco-editor";

// Only import the languages you need (PHP in this case)
import "monaco-editor/esm/vs/basic-languages/php/php.contribution";

// Import the editor workers
import editorWorker from "monaco-editor/esm/vs/editor/editor.worker?worker";

// Set up the worker
self.MonacoEnvironment = {
    getWorker() {
        return new editorWorker();
    },
};

// Make Monaco globally available
window.monaco = monaco;

// Import and make marked and highlight.js globally available for markdown rendering
import { marked } from "marked";
import hljs from "highlight.js";

window.marked = marked;
window.hljs = hljs;

// --- Global keyboard shortcuts for Quick Actions ---
(() => {
    let shortcutsBound = false;

    function getShortcutsConfig() {
        const el = document.getElementById('quick-actions-shortcuts');
        if (!el) return null;
        return {
            noteCreateUrl: el.dataset.noteCreateUrl,
            projectCreateUrl: el.dataset.projectCreateUrl,
            tagCreateUrl: el.dataset.tagCreateUrl,
            notesIndexUrl: el.dataset.notesIndexUrl,
        };
    }

    function isTypingInInput(target) {
        if (!target) return false;
        const tag = target.tagName?.toLowerCase();
        const editable = target.isContentEditable;
        return (
            editable ||
            tag === 'input' ||
            tag === 'textarea' ||
            tag === 'select'
        );
    }

    function navigate(url) {
        if (!url) return;
        window.location.href = url;
    }

    function bindShortcuts() {
        if (shortcutsBound) return;
        const cfg = getShortcutsConfig();
        if (!cfg) return; // Only bind when widget is present
        shortcutsBound = true;

        window.addEventListener('keydown', (e) => {
            const isMeta = e.metaKey || e.ctrlKey; // Support Cmd on macOS, Ctrl elsewhere
            if (!isMeta) return;
            if (isTypingInInput(e.target)) return;

            const key = e.key?.toLowerCase();
            switch (key) {
                case 'n':
                    if (cfg.noteCreateUrl) {
                        e.preventDefault();
                        navigate(cfg.noteCreateUrl);
                    }
                    break;
                case 'p':
                    if (cfg.projectCreateUrl) {
                        e.preventDefault();
                        navigate(cfg.projectCreateUrl);
                    }
                    break;
                case 't':
                    if (cfg.tagCreateUrl) {
                        e.preventDefault();
                        navigate(cfg.tagCreateUrl);
                    }
                    break;
                case 'k':
                    // Global search is disabled in the panel; as a fallback, go to All Notes
                    if (cfg.notesIndexUrl) {
                        e.preventDefault();
                        // Try to focus a search input after navigation
                        sessionStorage.setItem('focusSearchOnLoad', '1');
                        navigate(cfg.notesIndexUrl);
                    }
                    break;
            }
        });
    }

    function focusSearchIfRequested() {
        if (sessionStorage.getItem('focusSearchOnLoad') !== '1') return;
        sessionStorage.removeItem('focusSearchOnLoad');

        // Attempt to find a search field commonly used in Filament tables
        const selectors = [
            'input[type="search"]',
            'input[placeholder*="Search" i]',
            '.fi-input input[placeholder*="Search" i]'
        ];

        for (const sel of selectors) {
            const input = document.querySelector(sel);
            if (input && typeof input.focus === 'function') {
                setTimeout(() => input.focus(), 0);
                break;
            }
        }
    }

    // Bind on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            bindShortcuts();
            focusSearchIfRequested();
        });
    } else {
        bindShortcuts();
        focusSearchIfRequested();
    }
})();
