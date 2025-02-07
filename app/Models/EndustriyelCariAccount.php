<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EndustriyelCariAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_id',
        'firm_name',
        'date', // 📌 Tarih alanını ekledik!
        'tahsilat',
        'odeme',
        'alacak',
        'borc',
        'description',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date', // 📌 Tarih olarak kaydedilecek
    ];

    public function firm()
    {
        return $this->belongsTo(EndustriyelFirm::class, 'firm_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function getBakiyeAttribute()
    {
        return ($this->tahsilat + $this->alacak) - ($this->odeme + $this->borc);
    }
}
