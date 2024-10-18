<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientSpec;

class ClientSpecSeeder extends Seeder
{
    public function run()
    {
        ClientSpec::create([
            'client_address_id' => 1,  // Assuming 1 is the id from ClientAddressSeeder
            'jenis_batubara' => 'Antrasit',
            'grade' => 'A',
            'size' => 'Medium',
            'kalori' => '5000',
            'status' => 'active',
        ]);

        ClientSpec::create([
            'client_address_id' => 2,  // Assuming 2 is the id from ClientAddressSeeder
            'jenis_batubara' => 'Bituminous',
            'grade' => 'B',
            'size' => 'Small',
            'kalori' => '4000',
            'status' => 'inactive',
        ]);
    }
}
