<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function owner(){
        return $this->belongsTo(Freelancer::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function skills(){
        return $this->belongsToMany(Skill::class,'job_skills');
    }
    public function applicants(){
        return $this->hasMany(JobApplicants::class);
    }
}
