<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        Client::create([
            'company' => 'PT. ABC',
            'nama_purchasing' => 'John Doe',
            'alamat' => 'Jl. Merdeka No. 1',
            'email' => 'purchasing@abc.com',
            'nomor_telep' => '081234567890',
        ]);

        Client::create([
            'company' => 'PT. XYZ',
            'nama_purchasing' => 'Jane Smith',
            'alamat' => 'Jl. Pahlawan No. 99',
            'email' => 'purchasing@xyz.com',
            'nomor_telep' => '081234567891',
        ]);
    }
}
