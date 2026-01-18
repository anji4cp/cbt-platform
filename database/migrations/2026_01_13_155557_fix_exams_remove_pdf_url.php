<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'pdf_url')) {
                $table->dropColumn('pdf_url');
            }

            if (Schema::hasColumn('exams', 'answer_key')) {
                $table->dropColumn('answer_key');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->text('pdf_url')->nullable();
            $table->text('answer_key')->nullable();
        });
    }
};

