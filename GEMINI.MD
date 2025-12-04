I am using a Laravel 12 + Filament + Livewire app.
The Project edit page (`admin/projects/{id}/edit`) loads a fully working Markdown editor (EasyMDE via Filament’s `filament.forms.components.markdown-editor`).
But the Notes RelationManager modal (`admin/projects/{id}/edit` → Notes → Edit action modal) does NOT load the toolbar, preview, or EasyMDE UI.
It only shows a raw `<textarea>`.

Fix the modal so that the Markdown editor loads exactly the same UI as the full Project edit page.

Here are the requirements and constraints:

========================================

## ✦ PRIMARY FIX (WHY IT BREAKS)

Filament modals DO NOT automatically load external plugin assets (JS/CSS) or initialize heavy editors like EasyMDE.
The modal version of the Markdown editor needs to explicitly load the necessary JS and run EasyMDE after the modal DOM is present.

You must:

1. Ensure the RelationManager action’s form receives a **unique, stable HTML ID** for the markdown-editor textarea.
2. Ensure the modal triggers **EasyMDE initialization only after the modal is mounted**, not before.
3. Ensure that Filament is not stripping the toolbar config due to a nested form context.
4. Ensure the correct editor preset (`easy-mde` vs `commonmark`) is selected.

========================================

## ✦ REQUIREMENTS

### ✔ Make Notes modal use the FULL EasyMDE toolbar + config

Match exactly the working config used in the Project resource:

* toolbar buttons
* preview panel
* CSS classes
* CodeMirror setup
* Filament markdown-editor settings

### ✔ Make EasyMDE load *only once per modal mount*

Avoid duplicate initialization:

```
window.easyMdeInstances ??= {};
if (! window.easyMdeInstances[elementId]) {
    window.easyMdeInstances[elementId] = new EasyMDE({...});
}
```

### ✔ Add Alpine hook inside a Blade override

The modal must run:

```
setTimeout(() => { initMDE() }, 50–150ms)
```

### ✔ Patch Filament so modal loads the JS files

Either:

```php
Filament::registerScripts([...], true);
```

or inside the action:

```
->afterFormMounted(fn() => Filament::registerScripts([...], true))
```

### ✔ Editor must preserve Livewire bindings

Use a debounced update:

```
easyMde.codemirror.on('change', _.debounce(() => {
    $wire.set('data.content', easyMde.value(), true);
}, 300));
```

========================================

## ✦ TROUBLESHOOTING ITEMS TO FIX IF FOUND

Search the codebase and correct any of these:

1. The modal form redefines the `MarkdownEditor` without `->editor('easy-mde')`.
2. The modal action uses a nested schema inside a RelationManager that overrides plugin state.
3. Missing `@filamentScripts` or `@livewireScripts`.
4. Two different versions of Filament markdown editor in use.
5. Livewire morphdom re-renders before EasyMDE attaches.
6. Wrong `wire:key` causing the editor to re-mount endlessly.
7. Missing `x-ignore` or `wire:ignore` around the markdown editor.
8. Action using `->form([...])` instead of a closure that receives `$record`.
9. A stale cached view for `filament.forms.components.markdown-editor`. Clear with:

   ```
   php artisan view:clear
   php artisan filament:cache-reset
   ```

========================================

## ✦ IMPROVEMENTS TO APPLY

Apply these while fixing:

### ★ A. Give modal editor a bordered, padded UI

So it feels visually distinct:

```
border border-neutral-700 bg-neutral-900 rounded-xl shadow-inner p-2
```

### ★ B. Disable live preview in modals

Only show live preview in full-page editor.

### ★ C. Add Tailwind styling to distinguish toolbar vs content

Required classes:

* `bg-gray-900`
* `shadow-inner`
* `rounded-lg`
* Increased line height + padding

### ★ D. Add Alpine wrapper for toolbar buttons in modal

Ensure toolbar + editor layout match full-page.

### ★ E. Respect existing Livewire debounce settings

Prevent noisy AJAX calls.

========================================
