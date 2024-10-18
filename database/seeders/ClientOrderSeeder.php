<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientOrder;

class ClientOrderSeeder extends Seeder
{
    public function run()
    {
        ClientOrder::create([
            'no_po' => 'PO001',
            'stock_id' => 1,  // Assuming 1 is the id from StockSeeder
            'client_address_id' => 1,  // Assuming 1 is the id from ClientAddressSeeder
            'client_spec_id' => 1,  // Assuming 1 is the id from ClientSpecSeeder
            'status_order' => 'pending',
            'order_date' => now(),
            'total_order' => 50,
            'keterangan' => 'Urgent',
        ]);

        ClientOrder::create([
            'no_po' => 'PO002',
            'stock_id' => 2,  // Assuming 2 is the id from StockSeeder
            'client_address_id' => 2,  // Assuming 2 is the id from ClientAddressSeeder
            'client_spec_id' => 2,  // Assuming 2 is the id from ClientSpecSeeder
            'status_order' => 'shipped',
            'order_date' => now(),
            'total_order' => 75,
            'keterangan' => 'Standard delivery',
        ]);
    }
}
