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
