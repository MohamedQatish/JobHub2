<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Freelancer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = []; 
    protected $guard = 'freelancer';

    
    public function photo(){
        return $this->morphOne(Photo::class,'photable');
    }
    
    public function cv(){
        return $this->hasOne(Cv::class);
    }
    
    // public function skills(){
    //     return $this->hasMany(FreelancerSkill::class);
    // }

    public function skills(){
        return $this->belongsToMany(Skill::class,'freelancer_skills');
    }

    // public function favoriteCategories(){
    //     return $this->hasMany(FreelancerFavoriteCategories::class);
    // }

    public function favoriteCategories(){
        return $this->belongsToMany(Category::class,'freelancer_favorite_categories');
    }

    // public function favoriteJobs(){
    //     return $this->hasMany(FreelancerFavoriteJobs::class);
    // }

    public function favoriteJobs(){
        return $this->belongsToMany(Job::class,'freelancer_favorite_jobs');
    }

    public function applications(){
        return $this->hasMany(JobApplicants::class);
    }

    public function jobs(){
        return $this->hasMany(Job::class,'owner_id');
    }

    public function ratings(){
        return $this->hasMany(FreelancerRating::class);
    }
}
