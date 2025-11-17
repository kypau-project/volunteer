<?php

namespace App\Services;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UserImportExport
{
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Users Data");

        // Set column headers and widths
        $headers = [
            ["A", "ID", 8],
            ["B", "Name", 30],
            ["C", "Email", 35],
            ["D", "Phone", 15],
            ["E", "Role", 12],
            ["F", "Status", 10],
            ["G", "Address", 40],
            ["H", "Birth Date", 12],
            ["I", "Gender", 10],
            ["J", "Education", 25],
            ["K", "Institution", 35],
            ["L", "Skills", 40],
            ["M", "Experience", 40],
            ["N", "Created At", 20]
        ];

        // Apply headers and column widths
        foreach ($headers as [$column, $header, $width]) {
            $sheet->setCellValue($column . "1", $header);
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // Style for headers
        $headerStyle = [
            "font" => [
                "bold" => true,
                "color" => ["rgb" => "FFFFFF"],
                "size" => 11,
            ],
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "startColor" => ["rgb" => "4B5563"],
            ],
            "borders" => [
                "allBorders" => [
                    "borderStyle" => Border::BORDER_THIN,
                    "color" => ["rgb" => "000000"],
                ],
            ],
            "alignment" => [
                "horizontal" => Alignment::HORIZONTAL_CENTER,
                "vertical" => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A1:N1")->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(20);

        // Add data rows
        $users = User::with("profile")->get();
        $row = 2;

        // Style for alternating rows
        $evenRowStyle = [
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "startColor" => ["rgb" => "F3F4F6"],
            ],
        ];

        foreach ($users as $user) {
            // Set row data
            $data = [
                ["A", $user->id],
                ["B", $user->name],
                ["C", $user->email],
                ["D", $user->phone ?? ""],
                ["E", ucfirst($user->role)],
                ["F", $user->is_active ? "Active" : "Inactive"],
                ["G", $user->profile->address ?? ""],
                ["H", $user->profile->birth_date ?? ""],
                ["I", $user->profile->gender ?? ""],
                ["J", $user->profile->education ?? ""],
                ["K", $user->profile->institution ?? ""],
                ["L", $user->profile->skills ?? ""],
                ["M", $user->profile->experience ?? ""],
                ["N", $user->created_at->format("Y-m-d H:i:s")]
            ];

            foreach ($data as [$column, $value]) {
                $sheet->setCellValue($column . $row, $value);
            }

            // Apply alternating row style
            if ($row % 2 == 0) {
                $sheet->getStyle("A" . $row . ":N" . $row)->applyFromArray($evenRowStyle);
            }

            // Set row height
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;
        }

        // Style for all data
        $dataStyle = [
            "borders" => [
                "allBorders" => [
                    "borderStyle" => Border::BORDER_THIN,
                    "color" => ["rgb" => "000000"],
                ],
            ],
            "alignment" => [
                "vertical" => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A1:N" . ($row - 1))->applyFromArray($dataStyle);

        // Auto-filter
        $sheet->setAutoFilter("A1:N" . ($row - 1));

        // Freeze panes
        $sheet->freezePane("A2");

        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), "users_export_");
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $content = file_get_contents($tempFile);
        unlink($tempFile);

        return $content;
    }

    public function import($file)
    {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Skip header row
        array_shift($rows);

        // Gunakan UsersImport class untuk handle import
        $importer = new \App\Imports\UsersImport();
        return $importer->importFromArray($rows);
    }
}
