<?php

namespace App\Http\Controllers;

use App\Models\ProjectPage;
use Illuminate\View\View;

class ProjectPageController extends Controller
{
    /**
     * Display the project page
     */
    public function show(string $slug): View
    {
        $projectPage = ProjectPage::with('project.tags')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $project = $projectPage->project;

        return view('projects.show', [
            'projectPage' => $projectPage,
            'project' => $project,
            'content' => $projectPage->display_content,
            'tags' => $project->tags,
        ]);
    }
}
