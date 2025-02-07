<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndustriyelTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_id',
        'firm',
        'amount',
        'description',
        'type',
        'user_id',
        'date',
    ];
    
    
    public function firm()
    {
        return $this->belongsTo(EndustriyelFirm::class, 'firm_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
