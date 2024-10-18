<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientPayment;

class ClientPaymentSeeder extends Seeder
{
    public function run()
    {
        ClientPayment::create([
            'client_order_id' => 1,  // Assuming 1 is the id from ClientOrderSeeder
            'termin_ke' => 1,
            'jumlah_bayar' => 5000000,
            'tgl_jatuh_tempo' => now()->addDays(30),
            'tgl_bayar' => null,
            'payment_status' => 'unpaid',
            'keterangan' => 'First installment',
        ]);

        ClientPayment::create([
            'client_order_id' => 2,  // Assuming 2 is the id from ClientOrderSeeder
            'termin_ke' => 2,
            'jumlah_bayar' => 7000000,
            'tgl_jatuh_tempo' => now()->addDays(15),
            'tgl_bayar' => now(),
            'payment_status' => 'paid',
            'keterangan' => 'Second installment',
        ]);
    }
}
