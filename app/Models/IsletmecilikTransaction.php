<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsletmecilikTransaction extends Model
{
    use HasFactory;

    protected $table = 'isletmecilik_transactions'; // Tablo adı
    protected $fillable = [
        'firm_id',
        'firm',
        'amount',
        'description',
        'type',
        'user_id',
        'date',
    ];

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
        return $this->belongsTo(User::class, 'user_id');
    }
}
