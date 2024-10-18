<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'foto_item',
        'jenis_batubara',
        'grade',
        'size',
        'kalori',
        'jumlah_stok',
        'lokasi_simpan',
        'created_date',
        'updated_date',
        'harga_per_ton',
        'catatan',
        'nama_alias',
        'detail_stock',     
        'stock_before_update',
    ];

    // Relasi ke ClientOrder (one-to-many)
    public function orders()
    {
        return $this->hasMany(ClientOrder::class, 'stock_id');
    }

    // Event model untuk mengisi stock_before_update saat jumlah_stok berubah
    protected static function booted()
    {
        static::updating(function ($stock) {
            if ($stock->isDirty('jumlah_stok')) {
                // Set nilai stock_before_update menjadi nilai jumlah_stok sebelum diupdate
                $stock->stock_before_update = $stock->getOriginal('jumlah_stok');
            }
        });
    }
}
