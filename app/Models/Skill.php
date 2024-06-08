<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function freelancers(){
        return $this->belongsToMany(Freelancer::class,'freelancer_skills');
    }

    public function jobs(){
        return $this->belongsToMany(Job::class,'job_skills');
    }

    public function companyJobs()
    {
        return $this->belongsToMany(CompanyJob::class, 'company_job_skill');
    }


}
