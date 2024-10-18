<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_po',
        'stock_id',
        'client_address_id',
        'client_spec_id',
        'status_order',
        'order_date',
        'total_order',
        'total_tagihan',
        'keterangan',
    ];

    // Relasi ke Stock (many-to-one)
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    // Relasi ke ClientAddress (many-to-one)
    public function clientAddress()
    {
        return $this->belongsTo(ClientAddress::class, 'client_address_id');
    }
    // Relasi ke ClientSpec (many-to-one)
    public function clientSpec()
    {
        return $this->belongsTo(ClientSpec::class, 'client_spec_id');
    }

    // Relasi ke ClientPayment (one-to-many)
    public function payments()
    {
        return $this->hasMany(ClientPayment::class, 'client_order_id');
    }
    public function details()
    {
        return $this->hasMany(ClientOrderDetail::class, 'client_order_id');
    }
    public function getTotalJumlahOrderAttribute()
    {
        return $this->details()->sum('jumlah_order');
    }
    // Model ClientOrder.php
    public function client()
    {
        return $this->hasOneThrough(Client::class, ClientAddress::class, 'id', 'id', 'client_address_id', 'client_id');
    }

}

