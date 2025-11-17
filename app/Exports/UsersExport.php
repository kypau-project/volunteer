<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UsersExport
{
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headings
        $headings = [
            'Name',
            'Email',
            'Phone',
            'Role',
            'Status',
            'Address',
            'Skills',
            'Bio',
            'Total Hours',
            'Created At',
            'Last Login'
        ];

        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        foreach ($headings as $index => $heading) {
            $sheet->setCellValue($columns[$index] . '1', $heading);
        }

        // Get users data
        $users = User::with('profile')->get();
        $row = 2;

        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->name);
            $sheet->setCellValue('B' . $row, $user->email);
            $sheet->setCellValue('C' . $row, $user->phone ?? '');
            $sheet->setCellValue('D' . $row, $user->role);
            $sheet->setCellValue('E' . $row, $user->is_blocked ? 'Blocked' : 'Active');
            $sheet->setCellValue('F' . $row, $user->profile->address ?? '');
            $sheet->setCellValue('G' . $row, $user->profile->skills ?? '');
            $sheet->setCellValue('H' . $row, $user->profile->bio ?? '');
            $sheet->setCellValue('I' . $row, $user->profile->total_hours ?? 0);
            $sheet->setCellValue('J' . $row, $user->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('K' . $row, $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : '');

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }
}
