<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_id',
        'client_order_detail_id', // Tambahkan ini
        'termin_ke',
        'jumlah_bayar',
        'tgl_jatuh_tempo',
        'tgl_bayar',
        'payment_status',
        'keterangan',
    ];

    // Relasi ke ClientOrder (many-to-one)
    public function clientOrder()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id');
    }

    // Relasi ke ClientOrderDetail (many-to-one)
    public function clientOrderDetail()
    {
        return $this->belongsTo(ClientOrderDetail::class, 'client_order_detail_id');
    }
}

