<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'client_order_details';

    protected $fillable = [
        'client_order_id',
        'no_po',
        'jumlah_order',
        'status',
        'image', // Kolom image ditambahkan di sini
        'reason',
    ];

    // Relasi dengan model ClientOrder
    public function clientOrder()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id');
    }

    // Relasi dengan ClientPayment
    public function clientPayments()
    {
        return $this->hasMany(ClientPayment::class, 'client_order_detail_id');
    }
}
