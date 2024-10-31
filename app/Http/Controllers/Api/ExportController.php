<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Services\Order\OrderServiceInterface;
use App\Services\Voucher\VoucherServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(protected  OrderServiceInterface $orderService)
    {
    }


    public function exportOrder($id)
    {
        $order = $this->orderService->findById($id);
        $voucher = Voucher::where('id', $order->voucher_id)->firstOrFail();
        $pdf = Pdf::loadView('pdf.order', compact("order" , 'voucher'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="invoice'.$order->id.'_'.now()->format("Ymd").'pdf"');
    }

}
