<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyJobApplicant extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function companyJob()
    {
        return $this->belongsTo(CompanyJob::class);
    }
    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }
}
