<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'penerima',
        'alamat_lengkap',
        'nama_cp',
        'nomor_telp',
        'created_date',
        'updated_date',
    ];

    // Relasi ke Client (many-to-one)
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Relasi ke ClientOrder (one-to-many)
    public function orders()
    {
        return $this->hasMany(ClientOrder::class, 'client_address_id');
    }

    // Relasi ke ClientSpec (one-to-many)
    public function specs()
    {
        return $this->hasMany(ClientSpec::class, 'client_address_id');
    }
    
}

