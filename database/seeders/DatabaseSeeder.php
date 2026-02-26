<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // ← INI YANG TADI KURANG

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔐 SUPER ADMIN
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@cbt.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // 🏫 SEKOLAH TEST
        $school = \App\Models\School::firstOrCreate(
            ['school_id' => 'SMAN1TEST'],
            [
                'name' => 'SMA Negeri 1 Test',
                'status' => 'active',
                'trial_ends_at' => now()->addMonths(3),
                'subscription_ends_at' => now()->addYear(),
                'theme_color' => '#2563eb',
            ]
        );

        // 👨‍💼 ADMIN SEKOLAH
        User::firstOrCreate(
            ['email' => 'admin.sekolah@cbt.test'],
            [
                'name' => 'Admin SMA Negeri 1',
                'password' => Hash::make('password'),
                'role' => 'admin_school',
                'school_id' => $school->id,
            ]
        );

        // 📚 SISWA TEST
        \App\Models\Student::firstOrCreate(
            ['username' => 'budi001', 'school_id' => $school->id],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'exam_password' => null,
                'class' => 'X IPA 1',
                'is_active' => true,
            ]
        );

        \App\Models\Student::firstOrCreate(
            ['username' => 'ani001', 'school_id' => $school->id],
            [
                'name' => 'Ani Wijaya',
                'password' => Hash::make('password'),
                'exam_password' => null,
                'class' => 'X IPA 1',
                'is_active' => true,
            ]
        );

        \App\Models\Student::firstOrCreate(
            ['username' => 'citra001', 'school_id' => $school->id],
            [
                'name' => 'Citra Kusuma',
                'password' => Hash::make('password'),
                'exam_password' => null,
                'class' => 'X IPS 2',
                'is_active' => true,
            ]
        );

        // 📝 UJIAN TEST
        $exam = \App\Models\Exam::firstOrCreate(
            ['token' => 'MATH2026', 'school_id' => $school->id],
            [
                'subject' => 'Matematika Wajib',
                'total_questions' => 40,
                'duration_minutes' => 90,
                'min_submit_minutes' => 10,
                'show_score' => true,
                'is_active' => true,
            ]
        );

        // 📋 KELAS YANG DIIZINKAN UJIAN
        \App\Models\ExamClass::firstOrCreate(
            ['exam_id' => $exam->id, 'class_name' => 'X IPA 1']
        );

        \App\Models\ExamClass::firstOrCreate(
            ['exam_id' => $exam->id, 'class_name' => 'X IPS 2']
        );

        // 📄 PAKET SOAL
        \App\Models\ExamPackage::firstOrCreate(
            ['exam_id' => $exam->id, 'package_code' => 'PKG001'],
            [
                'pdf_url' => 'https://drive.google.com/file/d/1example/view?usp=drive_link',
                'answer_key' => [
                    '1' => 'B',
                    '2' => 'C',
                    '3' => 'A',
                ],
            ]
        );
    }
}
