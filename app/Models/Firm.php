<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Firm;


class Firm extends Model
{
    public function transactions()
    {
        return $this->hasMany(EndustriyelTransaction::class, 'firm_id');
    }
}
