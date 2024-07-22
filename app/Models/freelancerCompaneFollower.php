<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class freelancerCompaneFollower extends Model
{
    use HasFactory;
    protected $guraded=[];
    public function company()
    {
        return $this->hasMany(Company::class);
    }
    public function freelancer()
    {
        return $this->hasMany(Freelancer::class);
    }
}
