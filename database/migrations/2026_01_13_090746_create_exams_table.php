<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->string('token')->unique();
            $table->string('pdf_url');
            $table->integer('total_questions');
            $table->json('answer_keys'); // {"A": [...], "B": [...]}
            $table->integer('duration_minutes');
            $table->integer('min_submit_minutes');
            $table->boolean('show_score')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
