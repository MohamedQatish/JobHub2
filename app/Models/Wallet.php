<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable=['owner_id','owner_type','balance'];
    public function owner()
    {
        return $this->morphTo();
    }
}
