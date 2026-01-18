<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $schoolId = auth()->user()->school_id;

        $rows = \Maatwebsite\Excel\Facades\Excel::toArray([], $request->file('file'))[0];

        // buang header
        unset($rows[0]);

        $success = 0;
        $failed  = [];

        foreach ($rows as $index => $row) {

            $rowNumber = $index + 1;

            // NORMALISASI DATA (AMAN)
            $name     = isset($row[0]) ? trim($row[0]) : null;
            $username = isset($row[1]) ? trim($row[1]) : null;
            $class    = isset($row[2]) ? trim($row[2]) : null;
            $password = isset($row[3]) ? trim($row[3]) : null;

            // ⛔ SKIP BARIS KOSONG TOTAL
            if (! $name && ! $username && ! $class && ! $password) {
                continue;
            }

            // ⛔ VALIDASI WAJIB
            if (! $name || ! $username || ! $class || ! $password) {
                $failed[] = "Baris {$rowNumber}: Kolom wajib kosong (Nama / Username / Kelas / Password)";
                continue;
            }

            // ⛔ CEK DUPLIKAT USERNAME
            $exists = Student::where('school_id', $schoolId)
                ->where('username', $username)
                ->exists();

            if ($exists) {
                $failed[] = "Baris {$rowNumber}: Username '{$username}' sudah digunakan";
                continue;
            }

            // ✅ SIMPAN
            Student::create([
                'school_id' => $schoolId,
                'name'      => $name,
                'username'  => $username,
                'class'     => $class,
                'password'  => Hash::make($password),
                'is_active' => true,
            ]);

            $success++;
        }

        return back()->with([
            'import_success' => $success,
            'import_failed'  => $failed
        ]);
    }

}
