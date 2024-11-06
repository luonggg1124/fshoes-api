<?php

namespace App\Http\Controllers\Api;

use App\Exports\OrderExport;
use App\Exports\ProductExport;
use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Services\Order\OrderServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct(protected  OrderServiceInterface $orderService)
    {
    }


    public function exportInvoice($id)
    {
        $order = $this->orderService->findById($id);
        $voucher = Voucher::where('id', $order->voucher_id)->first();
        $pdf = Pdf::loadView('pdf.order', compact("order" , 'voucher'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="invoice'.$order->id.'_'.now()->format("Ymd").'pdf"');
    }
    public function exportOrder(Request $request){
            if($request->type=="excel"){
                return response(Excel::raw(new OrderExport,  \Maatwebsite\Excel\Excel::XLSX) ,200)
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', 'attachment; filename="orders_'.now()->format("Ymd").'csv"');
            }elseif ($request->type == 'csv') {
                return response(Excel::raw(new OrderExport, \Maatwebsite\Excel\Excel::CSV), 200)
                    ->header('Content-Type', "application/CSV")  // Correct content type for CSV
                    ->header('Content-Disposition', 'attachment; filename="orders_' . now()->format("Ymd") . '.csv"');  // Adjusted filename extension
            }

        return response()->json([]  , 500);
    }

    public function exportUser(Request $request){
        if($request->type=="excel"){
            return response(Excel::raw(new UserExport,  \Maatwebsite\Excel\Excel::XLSX) ,200)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="user_'.now()->format("Ymd").'xlsx"');
        }elseif ($request->type == 'csv') {
            return response(Excel::raw(new UserExport, \Maatwebsite\Excel\Excel::CSV), 200)
                ->header('Content-Type', "application/CSV")
                ->header('Content-Disposition', 'attachment; filename="user_' . now()->format("Ymd") . '.csv"');
        }

        return response()->json([]  , 500);
    }

    public function exportProduct(Request $request){
        if($request->type=="excel"){
            return response(Excel::raw(new ProductExport,  \Maatwebsite\Excel\Excel::XLSX) ,200)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="product_'.now()->format("Ymd").'xlsx"');
        }elseif ($request->type == 'csv') {
            return response(Excel::raw(new ProductExport, \Maatwebsite\Excel\Excel::CSV), 200)
                ->header('Content-Type', "application/CSV")
                ->header('Content-Disposition', 'attachment; filename="product_' . now()->format("Ymd") . '.csv"');
        }

        return response()->json([]  , 500);
    }

}
