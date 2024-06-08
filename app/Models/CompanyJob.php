<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyJob extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function company()
    {
        return $this->belongsTo(Company::class,'owner_id');
    }
    public function skills()
    {
        return $this->belongsToMany(skill::class,'company_job_skills');
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
