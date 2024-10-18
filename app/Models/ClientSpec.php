<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSpec extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_address_id',
        'jenis_batubara',
        'grade',
        'size',
        'kalori',
        'status',
    ];

    // Relasi ke ClientAddress (many-to-one)
    public function clientAddress()
    {
        return $this->belongsTo(ClientAddress::class, 'client_address_id');
    }
}
