<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function bannable(){
        return $this->morphTo();
    }
    
    public function bannedBy(){
        return $this->belongsTo(Admin::class,'banned_by');
    }
}
