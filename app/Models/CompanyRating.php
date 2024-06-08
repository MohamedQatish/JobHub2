<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRating extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function freelancers()
    {
    return $this->belongsTo(Freelancer::class);
    }
    public function companies()
    {
        return $this->belongsTo(Company::class);
    }
}
