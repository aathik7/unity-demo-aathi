<?php

namespace App\Exports;

use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('inventory_items')
            ->select(DB::raw("item_name, description, price, quantity, CASE WHEN active_flag = 1 THEN 'Active' ELSE 'INACTIVE' END"))
            ->get();
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['Name', 'Description', 'Price', 'Available Stocks', 'Status'];
    }
}
