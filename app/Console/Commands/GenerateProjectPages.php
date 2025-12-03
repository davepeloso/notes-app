<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectPage;
use Illuminate\Console\Command;

class GenerateProjectPages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'projects:generate-pages 
                            {--all : Generate pages for all projects}
                            {--missing : Only generate for projects without pages}
                            {--unpublish : Create as unpublished}';

    /**
     * The console command description.
     */
    protected $description = 'Generate project pages for existing projects';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Generating project pages...');
        $this->newLine();

        $query = Project::query();

        if ($this->option('missing')) {
            $query->whereDoesntHave('page');
            $this->info('📋 Generating pages for projects without pages...');
        } elseif ($this->option('all')) {
            $this->warn('⚠️  Generating pages for ALL projects (existing pages will be skipped)');
        } else {
            $this->error('Please specify --all or --missing option');
            return self::FAILURE;
        }

        $projects = $query->get();
        
        if ($projects->isEmpty()) {
            $this->warn('No projects found to process.');
            return self::SUCCESS;
        }

        $this->info("Found {$projects->count()} project(s) to process");
        $this->newLine();

        $bar = $this->output->createProgressBar($projects->count());
        $bar->start();

        $created = 0;
        $skipped = 0;

        foreach ($projects as $project) {
            // Skip if page already exists
            if ($project->page()->exists()) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $slug = ProjectPage::generateSlug($project->name);

            ProjectPage::create([
                'project_id' => $project->id,
                'slug' => $slug,
                'is_published' => !$this->option('unpublish'),
            ]);

            $created++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Created: {$created} page(s)");
        
        if ($skipped > 0) {
            $this->comment("⏭️  Skipped: {$skipped} page(s) (already exist)");
        }

        $this->newLine();
        $this->info('🎉 Done!');
        
        if ($created > 0) {
            $this->comment('View project pages at: /project/{slug}');
        }

        return self::SUCCESS;
    }
}
