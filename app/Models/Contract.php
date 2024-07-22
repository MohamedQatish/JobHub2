<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function job()
    {
        return $this->morphTo();
    }

    public function employer()
    {
        return $this->morphTo();
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }
}
