<?php

namespace App\Imports;

use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Support\Facades\Hash;

/**
 * Kelas untuk import user dari file Excel
 * Sekarang menggunakan PhpSpreadsheet langsung tanpa dependency pada maatwebsite/excel
 */
class UsersImport
{
    /**
     * Import users dari array data
     * Dipanggil oleh UserImportExport service
     */
    public function importFromArray(array $rows)
    {
        $importedCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                // Skip jika email kosong
                if (empty($row[2])) {
                    continue;
                }

                // Validasi email format
                if (!filter_var($row[2], FILTER_VALIDATE_EMAIL)) {
                    $errorCount++;
                    $errors[] = 'Row ' . ($index + 2) . ': Email "' . $row[2] . '" tidak valid';
                    continue;
                }

                // Validasi role
                $role = strtolower($row[4] ?? 'volunteer');
                if (!in_array($role, ['admin', 'coordinator', 'volunteer'])) {
                    $errorCount++;
                    $errors[] = 'Row ' . ($index + 2) . ': Role "' . $role . '" tidak valid. Gunakan: admin, coordinator, atau volunteer';
                    continue;
                }

                $user = User::updateOrCreate(
                    ['email' => $row[2]], // Email sebagai unique identifier
                    [
                        'name' => trim($row[1] ?? 'Unknown'),
                        'email' => $row[2],
                        'password' => Hash::make('password123'), // Default password
                        'phone' => trim($row[3] ?? '') ?: null,
                        'role' => $role,
                        'is_blocked' => (isset($row[5]) && strtolower($row[5]) === 'blocked') ? true : false,
                    ]
                );

                // Update atau create profile
                if ($user->profile) {
                    $user->profile->update([
                        'address' => trim($row[6] ?? '') ?: null,
                        'birth_date' => $row[7] ?? null,
                        'gender' => $row[8] ?? null,
                        'education' => $row[9] ?? null,
                        'institution' => trim($row[10] ?? '') ?: null,
                        'skills' => trim($row[11] ?? '') ?: null,
                        'experience' => trim($row[12] ?? '') ?: null,
                    ]);
                } else {
                    VolunteerProfile::create([
                        'user_id' => $user->id,
                        'address' => trim($row[6] ?? '') ?: null,
                        'birth_date' => $row[7] ?? null,
                        'gender' => $row[8] ?? null,
                        'education' => $row[9] ?? null,
                        'institution' => trim($row[10] ?? '') ?: null,
                        'skills' => trim($row[11] ?? '') ?: null,
                        'experience' => trim($row[12] ?? '') ?: null,
                    ]);
                }

                $importedCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = 'Row ' . ($index + 2) . ': ' . $e->getMessage();
            }
        }

        return [
            'success' => $importedCount,
            'failed' => $errorCount,
            'errors' => $errors
        ];
    }
}
