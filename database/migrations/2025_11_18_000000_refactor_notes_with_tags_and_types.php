<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop old notes table
        Schema::dropIfExists('notes');

        // Create projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3b82f6'); // Hex color
            $table->timestamps();
        });

        // Create tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color', 7)->default('#10b981'); // Hex color
            $table->boolean('is_flag')->default(false); // Flags are special tags
            $table->timestamps();
        });

        // Create notes table with type field
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['markdown', 'code', 'mixed'])->default('markdown');
            $table->longText('content')->nullable(); // Main content (markdown or code)
            $table->longText('code_content')->nullable(); // Secondary content for mixed type
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Create pivot table for note-tag relationship
        Schema::create('note_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['note_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_tag');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('projects');
    }
};
