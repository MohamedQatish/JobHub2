<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPostPackage extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function postPackage()
    {
        return $this->belongsTo(postPackage::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
