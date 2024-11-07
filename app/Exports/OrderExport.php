<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{

    public function collection()
    {
        return Order::all()->map(function ($order) {
            return [
                'id' => $order->id,                        // Changed 'ID' to 'id'
                'user_id' => $order->user_id,                    // Changed 'User ID' to 'user_id'
                'total_amount' => $order->total_amount,              // Changed 'Total Amount' to 'total_amount'
                'order_detail'=>$order->orderDetails,
                'payment_method' => $order->payment_method,            // Changed 'Payment Method' to 'payment_method'
                'payment_status' => $order->payment_status,            // Changed 'Payment Status' to 'payment_status'
                'shipping_method' => $order->shipping_method,           // Changed 'Shipping Method' to 'shipping_method'
                'shipping_cost' => $order->shipping_cost,             // Changed 'Shipping Cost' to 'shipping_cost'
                'tax_amount' => $order->tax_amount,                // Changed 'Tax Amount' to 'tax_amount'
                'amount_collected' => $order->amount_collected,          // Changed 'Amount Collected' to 'amount_collected'
                'receiver_full_name' => $order->receiver_full_name,        // Changed 'Receiver Full Name' to 'receiver_full_name'
                'address' => $order->address,                   // Changed 'Address' to 'address'
                'phone' => $order->phone,                     // Changed 'Phone' to 'phone'
                'city' => $order->city,                      // Changed 'City' to 'city'
                'country' => $order->country,                   // Changed 'Country' to 'country'
                'voucher_id' => $order->voucher,                // Changed 'Voucher ID' to 'voucher_id'
                'status' => $order->status,                    // Changed 'Status' to 'status'
                'note' => $order->note,                      // Changed 'Note' to 'note',
                'history'=>$order->orderHistory,
                'deleted_at' => $order->deleted_at,                // Changed 'Deleted At' to 'deleted_at'
                'created_at' => $order->created_at,                // Changed 'Created At' to 'created_at'
                'updated_at' => $order->updated_at                 // Changed 'Updated At' to 'updated_at'
            ];
        });
    }


    public
    function headings(): array
    {
        return [
            'ID',
            'User ID',
            'Total Amount',
            "Order Details",
            'Payment Method',
            'Payment Status',
            'Shipping Method',
            'Shipping Cost',
            'Tax Amount',
            'Amount Collected',
            'Receiver Full Name',
            'Address',
            'Phone',
            'City',
            'Country',
            'Voucher ID',
            'Status',
            'Note',
            'History',
            'Deleted At',
            'Created At',
            'Updated At'
        ];
    }

    public
    function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],  // Bold header row
        ];
    }

    public
    function columnWidths(): array
    {
        return [
            'A' => 15, // ID column
            'B' => 15, // User ID column
            'C' => 15, // Total Amount column
            'D' => 15, // Payment Method column
            'E' => 15, // Payment Status column
            'F' => 15, // Shipping Method column
            'G' => 15, // Shipping Cost column
            'H' => 15, // Tax Amount column
            'I' => 15, // Amount Collected column
            'J' => 15, // Receiver Full Name column
            'K' => 20, // Address column
            'L' => 15, // Phone column
            'M' => 15, // City column
            'N' => 15, // Country column
            'O' => 15, // Voucher ID column
            'P' => 15, // Status column
            'Q' => 25, // Note column
            'R' => 15, // Deleted At column
            'S' => 15, // Created At column
            'T' => 15  // Updated At column
        ];
    }
}
