<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company',
        'company_code', // Menambahkan company_code ke fillable
        'nama_purchasing',
        'alamat',
        'email',
        'nomor_telep',
        'deletion_reason',
        'deletion_requested',
        'deletion_approved', 
        'created_date',
        'updated_date',
    ];

    // Relasi ke ClientAddress (one-to-many)
    public function addresses()
    {
        return $this->hasMany(ClientAddress::class, 'client_id');
    }

    // Relasi ke ClientOrder (one-to-many)
    // public function orders()
    // {
    //     return $this->hasMany(ClientOrder::class, 'client_id');
    // }

    // Relasi ke ClientOrder melalui ClientAddress (one-to-many-through)
    public function orders()
    {
        return $this->hasManyThrough(ClientOrder::class, ClientAddress::class, 'client_id', 'client_address_id');
    }
    public function isDeletionRequested()
    {
        return $this->deletion_requested;
    }

    // Metode untuk mengecek apakah penghapusan sudah disetujui
    public function isDeletionApproved()
    {
        return $this->deletion_approved;
    }

    // Relasi ke Notification (one-to-many)
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'client_id');
    }

    // Metode untuk menghasilkan company_code dari nama perusahaan
    public static function generateCompanyCode($company)
    {
        // Pisahkan nama perusahaan berdasarkan spasi
        $words = explode(' ', $company);

        // Ambil dua huruf pertama dari kata pertama, tanpa mengubah kapitalisasi
        $initials = substr($words[0], 0, 2); // Pertahankan huruf besar-kecil dari kata pertama

        // Jika ada kata kedua atau lebih, ambil huruf pertama dari setiap kata dan ubah ke huruf besar
        if (count($words) > 1) {
            foreach (array_slice($words, 1) as $word) {
                $initials .= strtoupper(substr($word, 0, 1)); // Huruf pertama dari kata kedua dan seterusnya diubah ke huruf besar
            }
        }

        // Cek apakah kode sudah ada di database, jika ya, tambahkan angka agar unik
        $code = $initials;
        $counter = 1;
        while (self::where('company_code', $code)->exists()) {
            $code = $initials . $counter;
            $counter++;
        }

        return $code;
    }

    // Override metode boot untuk menyetel company_code saat membuat client baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $client->company_code = self::generateCompanyCode($client->company);
        });
    }
    
}
