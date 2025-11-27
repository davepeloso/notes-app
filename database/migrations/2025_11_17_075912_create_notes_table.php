<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("notes", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->longText("markdown")->nullable(); // Markdown body text
            $table->longText("php_code")->nullable(); // Monaco PHP editor content
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("notes");
    }
};
