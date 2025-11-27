<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class MonacoNoteController extends Controller
{
    public function edit(Note $note)
    {
        return view('monaco.note', [
            'note' => $note,
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $data = $request->validate([
            'code_content' => ['nullable', 'string'],
        ]);

        $note->code_content = $data['code_content'] ?? '';
        $note->save();

        // Redirect back to the Filament edit page for this note
        return redirect(url('/admin/notes/' . $note->id . '/edit'))
            ->with('status', 'Code updated via Monaco editor.');
    }
}
