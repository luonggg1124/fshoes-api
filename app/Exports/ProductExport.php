<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Get the data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all()->map(function ($product) {
            return [
                'id'                => $product->id,
                'name'              => $product->name,
                'slug'              => $product->slug,
                'price'             => $product->price,
                'image_url'         => $product->image_url,
                'short_description' => $product->short_description,
                'description'       => $product->description,
                'sku'               => $product->sku,
                'status'            => $product->status,
                'qty_sold'          => $product->qty_sold,
                'stock_qty'         => $product->stock_qty,
                'image'             => $product->images,
                'reviews'           => $product->reviews,
                'deleted_at'         => $product->deleted_at,
                'created_at'        => $product->created_at,
                'updated_at'        => $product->updated_at,
            ];
        });
    }

    /**
     * Define the column headings for the export file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Slug',
            'Price',
            'Image URL',
            'Short Description',
            'Description',
            'SKU',
            'Status',
            'Qty Sold',
            'Stock Qty',
            'Image',
            'Reviews',
            'Deleted At',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Set the styles for the export file.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],  // Bold header row
        ];
    }

    /**
     * Set the width for the columns.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10, // ID column
            'B' => 20, // Name column
            'C' => 20, // Slug column
            'D' => 15, // Price column
            'E' => 25, // Image URL column
            'F' => 30, // Short Description column
            'G' => 40, // Description column
            'H' => 15, // SKU column
            'I' => 15, // Status column
            'J' => 15, // Qty Sold column
            'K' => 15, // Stock Qty column
            'X'=>15,
            'Z' => 15,
            'L' => 20, // Deleted At column
            'M' => 20, // Created At column
            'N' => 20, // Updated At column
        ];
    }
}
