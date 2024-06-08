<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function specialization(){
        return $this->belongsTo(Specialization::class);
    }

    public function favoriedBy(){
        return $this->belongsToMany(Freelancer::class,'freelancer_favorite_categories');
    }
    public function companyJobs()
    {
        return $this->hasMany(CompanyJob::class);
    }
    public function jobs(){
        return $this->hasMany(Job::class);
    }
}
