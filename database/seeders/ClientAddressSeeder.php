<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientAddress;

class ClientAddressSeeder extends Seeder
{
    public function run()
    {
        ClientAddress::create([
            'client_id' => 1,  // Assuming 1 is the id from ClientSeeder
            'penerima' => 'John Doe',
            'alamat_lengkap' => 'Jl. Merdeka No. 1, Jakarta',
            'nama_cp' => 'John Doe',
            'nomor_telp' => '081234567890',
        ]);

        ClientAddress::create([
            'client_id' => 2,  // Assuming 2 is the id from ClientSeeder
            'penerima' => 'Jane Smith',
            'alamat_lengkap' => 'Jl. Pahlawan No. 99, Surabaya',
            'nama_cp' => 'Jane Smith',
            'nomor_telp' => '081234567891',
        ]);
    }
}
