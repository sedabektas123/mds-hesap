<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndustriyelFirm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Transactions (işlemler) ile ilişki.
     */
    public function transactions()
    {
        return $this->hasMany(EndustriyelTransaction::class, 'firm_id');
    }

    /**
     * Cari hesaplar ile ilişki.
     */
    public function cariAccounts()
    {
        return $this->hasMany(EndustriyelCariAccount::class, 'firm_id');
    }
}
