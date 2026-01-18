<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'answer_keys')) {
                $table->dropColumn('answer_keys');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->text('answer_keys')->nullable();
        });
    }
};

