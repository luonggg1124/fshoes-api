<?php

namespace App\Exports;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Models\User\UserProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return User::all()->map(function($user) {
            return [
                $user->id,
                $user->nickname,
                $user->name,
                $user->is_admin,
                $user->status,
                $user->email,
                $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : null,
                $user->password,
                $user->google_id,
                $user->remember_token,
                $user->addresses,
                $user->interestingCategories,
                $user->favoriteProducts,
                $user->deleted_at ? $user->deleted_at->format('Y-m-d H:i:s') : null,
                $user->created_at->format('Y-m-d H:i:s'),
                $user->updated_at->format('Y-m-d H:i:s'),
                $user->group_id,
            ];
        });
    }


    public function headings(): array
    {
        return [
            'ID',                 // ID column
            'Nickname',           // Nickname column
            'Name',               // Name column
            'Is Admin',           // Is Admin column
            'Status',             // Status column
            'Email',              // Email column
            'Email Verified At',  // Email Verified At column
            'Password',           // Password column
            'Google ID',          // Google ID column
            'Remember Token',     // Remember Token column
            'Addresses',
            "Interesting Categories",
            "Favorite Products",
            'Deleted At',         // Deleted At column
            'Created At',         // Created At column
            'Updated At',         // Updated At column
            'Group ID',           // Group ID column
        ];
    }

    // Apply styles to the spreadsheet (make the header row bold)
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],  // Bold header row
        ];
    }

    // Set column widths
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // ID column
            'B' => 20,  // Nickname column
            'C' => 25,  // Name column
            'D' => 15,  // Is Admin column
            'E' => 15,  // Status column
            'F' => 30,  // Email column
            'G' => 20,  // Email Verified At column
            'H' => 35,  // Password column
            'I' => 20,  // Google ID column
            'J' => 20,  // Remember Token column
            'S'=>20,
            'X'=>20,
            'V'=>20,
            'K' => 20,  // Deleted At column
            'L' => 20,  // Created At column
            'M' => 20,  // Updated At column
            'N' => 15,  // Group ID column
        ];
    }

}
