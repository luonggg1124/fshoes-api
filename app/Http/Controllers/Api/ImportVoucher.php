<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\VoucherImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportVoucher extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new VoucherImport(), $request->file('vouchers'));
        return redirect('/')->with('success', 'All good!');
    }
}
