Here are the big changes you’ll want to adjust in your code.

The upgrade guide for v4 states that what used to be separate packages (Forms, Tables, Infolists, Actions, etc) are now more unified under the “Panels” / “Resources” concept, and the directory structure and namespace hygiene have been improved.
Filament
+1

Example: The write-up notes:

“In table instead of actions() now it’s recordActions(). In form and infolist, instead of injecting Form or Infolist classes, it changes to inject Schema.”
Filament Examples
+1

The “What’s new in v4” doc says:

“Instead of having separate Action classes for each context, all actions now use a single Filament\Actions namespace.”
Filament

The upgrade guide mentions a new default directory structure for resources & clusters, which ties in with namespace changes.
Filament

Among the high-impact changes: the default filesystem disk constant changed, file visibility changed, table filters defer by default, etc. While these aren’t strictly namespace changes, they often accompany the structural shifts.

What To Watch / Update in Your Codebase

Given your professional PHP/Laravel workflow (you mentioned Laravel, ImageMagick, etc) you’ll likely need to scan for these items.

Import / Use statements

If you were referencing classes under older namespaces like Filament\Resources\Form, Filament\Resources\Table, etc, those may have moved. (Older docs show Filament\Resources\Form → Filament\Forms\Form for example in earlier version upgrades)
Filament

Update references to actions: earlier you might have had separate FormAction, TableAction, etc; now you’ll likely use Filament\Actions\Action (or similar) under a unified namespace.

Update namespace references for form/infolist schema components: older code that used ->form(...) or ->infolist(...) may now reference a unified Schema or similar approach.

Resource, Cluster and Directory Structure

The upgrade script includes a command php artisan filament:upgrade-directory-structure-to-v4 which, if used, will move your resource files (and namespaces) to conform with v4 defaults.
Filament
+1

If you skip the directory restructure, you can still stay on v4 but you’ll need to ensure namespaces still align with any moved classes.

Custom Components & Plugins

You’ll need to update any custom components or plugin integrations: they may assume older namespaces and may break under v4. The discussion mentions plugin namespaces needing updates.
Filament Examples

For example: imports like use Awcodes\FilamentBadgeableColumn\… changed in plugin context.

Method signature and behavior changes

For example, make() signatures changed for Entry::make(), Column::make(), etc.
Filament

While not strictly namespace, these API changes often accompany new namespace structure and resource re-organization.

Specific Namespace “Splits” / Migrations You Should Search For

While a full list isn’t in the docs, here are specific namespace migrations to search in your codebase (you can grep for them):

Filament\Forms\Components\… vs older Filament\Resources\Forms\Components\…

Filament\Tables\Columns\… vs older Filament\Resources\Tables\Columns\…

Filament\Actions\Action being used instead of FormAction, TableAction, etc

Schema usage: in forms/infolists: e.g., ->schema([...]) rather than ->form([...]) or ->infolist([...])

Namespace changes for panel providers / resource classes: ensure namespace App\Filament\Resources matches expected v4 structure

Blade component changes: some Blade components may have moved namespaces or package names (less common but worth scanning)


Run these from your Laravel project root.

A. Find old Form namespaces
grep -RIn "Filament\\Resources\\Form" app/
grep -RIn "Filament\\Forms\\Form" app/

B. Find old Table namespaces
grep -RIn "Filament\\Resources\\Table" app/
grep -RIn "Filament\\Tables\\Table" app/

C. Old component namespaces
grep -RIn "Filament\\Resources\\Forms\\Components" app/
grep -RIn "Filament\\Resources\\Tables\\Columns" app/
grep -RIn "Filament\\Forms\\Components" app/
grep -RIn "Filament\\Tables\\Columns" app/

D. Deprecated action classes (v4 unifies them)
grep -RIn "FormAction" app/
grep -RIn "TableAction" app/
grep -RIn "BulkAction" app/
grep -RIn "HeaderAction" app/
grep -RIn "CreateAction" app/
grep -RIn "EditAction" app/
grep -RIn "DeleteAction" app/


(Everything should now live under Filament\Actions\* in v4.)

E. Infolist old namespaces
grep -RIn "Filament\\Infolists" app/
grep -RIn "Filament\\Resources\\Infolists" app/

F. Check for old method signatures
grep -RIn "->form(" app/
grep -RIn "->infolist(" app/
grep -RIn "->actions(" app/
grep -RIn "->filters(" app/


In v4 these often become:

->schema([...])

->recordActions([...])

->modifyQuery…() changes

filters are deferred by default

G. Plugins referencing old namespaces
grep -RIn "Awcodes" app/
grep -RIn "Flowframe" app/
grep -RIn "pxlrbt" app/


(Many Filament plugins updated namespaces for v4.)

H. Check for older directory structure assumptions
grep -RIn "App\\Filament\\Resources" app/


Useful if you are migrating to the new v4 “clustered” directory structure.

📣 2. General Notice for the Dev Team

(Paste this in Slack, Jira, Linear, GitHub issue — it’s written clean and professional.)

🚨 NOTICE: Filament v4 Namespace + API Changes

We’re upgrading to Filament v4, which includes major namespace consolidation and class reorganization. Please be aware of the following changes while modifying or reviewing code:

1. Namespaces have been unified

Many components previously under
Filament\Resources\{Forms,Tables,Infolists}
are now under simpler top-level namespaces such as:

Filament\Forms

Filament\Tables

Filament\Schemas

Filament\Actions (all actions are now unified under this)

2. Form & Infolist definitions now use ->schema()

Replace:

->form([...])
->infolist([...])


with:

->schema([
// fields
])

3. Table action methods have changed

Replace:

->actions([...])


with:

->recordActions([...])

4. Old action classes (FormAction, TableAction, etc.) are deprecated

All actions now live under:

Filament\Actions\*

5. Many Filament plugins changed namespaces

If a plugin fails to load or throws a class-not-found error, update its imports to the v4 versions.

6. Run the directory-structure upgrader if needed

If adopting v4 conventions:

php artisan filament:upgrade-directory-structure-to-v4

7. Please run the provided grep checklist

This will reveal any outdated namespaces or method calls that must be updated for v4 compatibility.
