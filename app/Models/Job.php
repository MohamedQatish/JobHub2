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
    public function worker(){
        return $this->belongsTo(Freelancer::class,'worker_id');
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
    public function reported(){
        return $this->morphMany(Report::class,'reportable');
    }
    public function contracts()
    {
        return $this->morphMany(Contract::class, 'job');
    }
}
