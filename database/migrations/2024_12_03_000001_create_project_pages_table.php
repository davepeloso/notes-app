<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->boolean('is_published')->default(true);
            $table->text('custom_content')->nullable(); // Optional override for README
            $table->json('meta_data')->nullable(); // Any extra metadata
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_pages');
    }
};
