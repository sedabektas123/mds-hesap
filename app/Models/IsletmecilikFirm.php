<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsletmecilikFirm extends Model
{
    use HasFactory;

    protected $table = 'isletmecilik_firms'; // Tablo adı
    protected $fillable = [
        'name',
    ];

    /**
     * Transactions (işlemler) ile ilişki.
     */
    public function transactions()
    {
        return $this->hasMany(IsletmecilikTransaction::class, 'firm_id');
    }

    /**
     * Cari hesaplar ile ilişki.
     */
    public function cariAccounts()
    {
        return $this->hasMany(IsletmecilikCariAccount::class, 'firm_id');
    }
}
