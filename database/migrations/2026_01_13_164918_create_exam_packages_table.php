<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')
                  ->constrained('exams')
                  ->cascadeOnDelete();
            $table->string('package_code');
            $table->text('pdf_url');
            $table->json('answer_key');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_packages');
    }
};
