<?php

namespace App\Imports;

use App\Models\Voucher;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class VoucherImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Voucher([
            "code" => $row[0],
            "discount" => $row[1],
            "date_start" =>Carbon::parse($row[2])->format('Y-m-d H:i:s') ,
            "date_end" => Carbon::parse($row[3])->format('Y-m-d H:i:s') ,
            "min_total_amount" => $row[4],
            "quantity" => $row[5],
            "status" => $row[6]
        ]);
    }
}
