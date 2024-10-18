<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    public function run()
    {
        Stock::create([
            'foto_item' => 'item1.jpg',
            'jenis_batubara' => 'Antrasit',
            'grade' => 'A',
            'size' => 'Medium',
            'kalori' => '5000',
            'jumlah_stok' => 100,
            'lokasi_simpan' => 'Gudang 1',
            'harga_per_ton' => '1000000',
            'catatan' => 'High quality',
            'nama_alias' => 'ABC-5000',
        ]);

        Stock::create([
            'foto_item' => 'item2.jpg',
            'jenis_batubara' => 'Bituminous',
            'grade' => 'B',
            'size' => 'Small',
            'kalori' => '4000',
            'jumlah_stok' => 200,
            'lokasi_simpan' => 'Gudang 2',
            'harga_per_ton' => '900000',
            'catatan' => 'Low sulfur content',
            'nama_alias' => 'XYZ-4000',
        ]);
    }
}
