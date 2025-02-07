<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IsletmecilikCariAccount extends Model
{
    use HasFactory;

    protected $table = 'isletmecilik_cari_accounts'; // Tablo adı

    protected $fillable = [
        'firm_id',
        'firm_name', // 🛑 Buraya eklemeyi unutma!
        'date',         // Bunu eklemeniz şart
        'tahsilat',
        'odeme',
        'alacak',
        'borc',
        'description',
        'user_id',
    ];

    /**
     * Tarih alanları için Carbon kullanımı.
     */
    protected $dates = ['islem_tarihi'];

    /**
     * Firma ile ilişki tanımı.
     */
    public function firm()
    {
        return $this->belongsTo(IsletmecilikFirm::class, 'firm_id');
    }

    /**
     * Kullanıcı ile ilişki tanımı.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Bakiye hesaplama - Getter
     */
    public function getBakiyeAttribute()
    {
        return ($this->tahsilat + $this->alacak) - ($this->odeme + $this->borc);
    }

    /**
     * Tarih formatını değiştirmek için accessor
     */
    public function getFormattedIslemTarihiAttribute()
    {
        return $this->islem_tarihi ? Carbon::parse($this->islem_tarihi)->format('d/m/Y') : '-';
    }
}
