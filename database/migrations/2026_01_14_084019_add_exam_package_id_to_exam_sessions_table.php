<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_package_id')->nullable()->after('student_id');

            $table->foreign('exam_package_id')
                ->references('id')
                ->on('exam_packages')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropForeign(['exam_package_id']);
            $table->dropColumn('exam_package_id');
        });
    }

};
