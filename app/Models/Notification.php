<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user', 'position', 'read_at', 'client_id'];  // Tambahkan client_id

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
