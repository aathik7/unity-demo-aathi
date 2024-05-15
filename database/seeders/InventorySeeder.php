<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['item_name' => 'Cricket Bat', 'description' => 'cricket bat size m', 'quantity' => '10', 'price' => '1000'],
            ['item_name' => 'Cricket Ball', 'description' => 'cricket ball weight m', 'quantity' => '25', 'price' => '100'],
            ['item_name' => 'Box TV', 'description' => 'vintage box tv', 'quantity' => '50', 'price' => '2500']
        ];

        foreach ($items as $item ) {
        	InventoryItem::create($item);   	
        }
    }
}
