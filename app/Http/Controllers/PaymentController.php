<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $guarded=[];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

}
