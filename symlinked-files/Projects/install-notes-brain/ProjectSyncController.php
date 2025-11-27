<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectSyncController extends Controller
{
    /**
     * Sync multiple projects from project analyzer
     * 
     * POST /api/sync/projects
     * 
     * Payload:
     * {
     *   "projects": [
     *     {
     *       "project_data": {...},
     *       "note_data": {...},
     *       "tags": [...],
     *       "flags": [...]
     *     }
     *   ]
     * }
     */
    public function syncProjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'projects' => 'required|array',
            'projects.*.project_data' => 'required|array',
            'projects.*.note_data' => 'required|array',
            'projects.*.tags' => 'array',
            'projects.*.flags' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $results = [];
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($request->projects as $projectData) {
                try {
                    $result = $this->syncSingleProject($projectData);
                    $results[] = $result;
                } catch (\Exception $e) {
                    $errors[] = [
                        'project' => $projectData['project_data']['name'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            if (empty($errors)) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Projects synced successfully',
                    'synced' => count($results),
                    'results' => $results
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Some projects failed to sync',
                    'synced' => count($results),
                    'failed' => count($errors),
                    'errors' => $errors
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sync failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync a single project
     */
    protected function syncSingleProject(array $data): array
    {
        // 1. Create or update project
        $project = $this->createOrUpdateProject($data['project_data']);

        // 2. Create or get tags
        $tagIds = [];
        if (!empty($data['tags'])) {
            $tagIds = $this->ensureTags($data['tags']);
        }

        // 3. Create or get flags
        $flagIds = [];
        if (!empty($data['flags'])) {
            $flagIds = $this->ensureTags($data['flags']);
        }

        // 4. Create or update note
        $noteData = $data['note_data'];
        $noteData['project_id'] = $project->id;
        $note = $this->createOrUpdateNote($noteData);

        // 5. Sync tags and flags to note
        $allTagIds = array_merge($tagIds, $flagIds);
        $note->tags()->sync($allTagIds);

        return [
            'project_id' => $project->id,
            'project_name' => $project->name,
            'note_id' => $note->id,
            'note_title' => $note->title,
            'tags_attached' => count($allTagIds)
        ];
    }

    /**
     * Create or update a project
     */
    protected function createOrUpdateProject(array $data): Project
    {
        // Try to find existing project by name
        $project = Project::where('name', $data['name'])->first();

        if ($project) {
            // Update existing
            $project->update([
                'description' => $data['description'] ?? $project->description,
                'color' => $data['color'] ?? $project->color,
            ]);
        } else {
            // Create new
            $project = Project::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'color' => $data['color'] ?? '#3b82f6',
            ]);
        }

        return $project;
    }

    /**
     * Create or update a note
     */
    protected function createOrUpdateNote(array $data): Note
    {
        // Try to find existing note by title and project
        $note = Note::where('title', $data['title'])
            ->where('project_id', $data['project_id'])
            ->first();

        if ($note) {
            // Update existing
            $note->update([
                'type' => $data['type'] ?? $note->type,
                'content' => $data['content'] ?? $note->content,
                'code_content' => $data['code_content'] ?? $note->code_content,
            ]);
        } else {
            // Create new
            $note = Note::create([
                'title' => $data['title'],
                'type' => $data['type'] ?? 'mixed',
                'content' => $data['content'] ?? null,
                'code_content' => $data['code_content'] ?? null,
                'project_id' => $data['project_id'],
            ]);
        }

        return $note;
    }

    /**
     * Ensure tags exist and return their IDs
     */
    protected function ensureTags(array $tags): array
    {
        $tagIds = [];

        foreach ($tags as $tagData) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagData['name']],
                [
                    'color' => $tagData['color'] ?? '#10b981',
                    'is_flag' => $tagData['is_flag'] ?? false,
                ]
            );

            $tagIds[] = $tag->id;
        }

        return $tagIds;
    }

    /**
     * Get all projects with their notes and tags
     * 
     * GET /api/projects
     */
    public function index()
    {
        $projects = Project::with(['notes.tags'])
            ->withCount('notes')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'projects' => $projects
        ]);
    }

    /**
     * Get a single project with full details
     * 
     * GET /api/projects/{id}
     */
    public function show(Project $project)
    {
        $project->load(['notes.tags', 'notes' => function ($query) {
            $query->orderBy('updated_at', 'desc');
        }]);

        return response()->json([
            'success' => true,
            'project' => $project
        ]);
    }

    /**
     * Search projects by various criteria
     * 
     * GET /api/projects/search?tag=laravel&flag=revenue-generating
     */
    public function search(Request $request)
    {
        $query = Project::with(['notes.tags']);

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('notes.tags', function ($q) use ($request) {
                $q->where('name', $request->tag)
                  ->where('is_flag', false);
            });
        }

        // Filter by flag
        if ($request->has('flag')) {
            $query->whereHas('notes.tags', function ($q) use ($request) {
                $q->where('name', $request->flag)
                  ->where('is_flag', true);
            });
        }

        // Text search in project name/description
        if ($request->has('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        $projects = $query->get();

        return response()->json([
            'success' => true,
            'count' => $projects->count(),
            'projects' => $projects
        ]);
    }

    /**
     * Get sync statistics
     * 
     * GET /api/sync/stats
     */
    public function stats()
    {
        $stats = [
            'total_projects' => Project::count(),
            'total_notes' => Note::count(),
            'total_tags' => Tag::where('is_flag', false)->count(),
            'total_flags' => Tag::where('is_flag', true)->count(),
            'projects_with_notes' => Project::has('notes')->count(),
            'recent_syncs' => Note::where('title', 'like', '%[Analysis]')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get(['id', 'title', 'updated_at']),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
