<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicants extends Model
{
    use HasFactory;
    protected $guarded = []; 
    public function job(){
        return $this->belongsTo(Job::class);
    }
    public function freelancer(){
        return $this->belongsTo(Freelancer::class);
    }
}
