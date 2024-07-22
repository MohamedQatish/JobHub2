<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPackage extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function companyPostPackage()
    {
        return $this->hasMany(CompanyPostPackage::class);
    }
}
