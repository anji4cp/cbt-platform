<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class StudentsImport implements ToCollection, WithHeadingRow
{
    protected int $schoolId;

    // 🔎 LAPORAN DETAIL
    public array $successRows = [];
    public array $failedRows  = [];

    public function __construct(int $schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Nomor baris ASLI di Excel (karena header)
            $rowNumber = $index + 2;

            // 🔴 VALIDASI WAJIB
            if (
                empty($row['name']) ||
                empty($row['username']) ||
                empty($row['class']) ||
                empty($row['password'])
            ) {
                $this->failedRows[] = [
                    'row'    => $rowNumber,
                    'reason' => 'Data tidak lengkap (nama / username / kelas / password kosong)',
                ];
                continue;
            }

            // 🔴 DUPLIKAT USERNAME
            $exists = Student::where('school_id', $this->schoolId)
                ->where('username', $row['username'])
                ->exists();

            if ($exists) {
                $this->failedRows[] = [
                    'row'    => $rowNumber,
                    'reason' => 'Username sudah terdaftar',
                ];
                continue;
            }

            // ✅ SIMPAN
            Student::create([
                'school_id'     => $this->schoolId,
                'name'          => trim($row['name']),
                'username'      => trim($row['username']),
                'class'         => trim($row['class']),
                'password'      => Hash::make($row['password']),
                'exam_password' => $row['password'],
                'is_active'     => true,
            ]);

            // 🟢 CATAT SUKSES
            $this->successRows[] = [
                'row'      => $rowNumber,
                'username' => $row['username'],
                'name'     => $row['name'],
                'class'    => $row['class'],
            ];
        }
    }
}
