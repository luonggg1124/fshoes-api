<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\VoucherImport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;

class ImportVoucher extends Controller
{
    public function import(Request $request)
    {
        try {
            Excel::import(new VoucherImport(), $request->file('vouchers'));
            return response()->json(["message"=>"Import voucher successfully"],200);
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                return response()->json(['message' => "The voucher code already exists. Please choose a different value."], 422);
            }
            return response()->json(['message' => "Something went wrong. Please try again later."], 500);
        } catch (Exception $exception) {
            return response()->json("An unexpected error occurred. Please try again.", 500);
        }

    }
}

