<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerRating extends Model
{
    use HasFactory;
    protected $guarded = [];
    


    public function freelancer(){
        $this->belongsTo(Freelancer::class,'freelancer_id');
    }
    public function user(){
        $this->belongsTo(Freelancer::class,'user_id');
    }
}
