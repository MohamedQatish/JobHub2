<?php

namespace App\Traits;

use App\Mail\codeMail;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait VerificationTrait{

    public function sendCode($freelancer){
        try{
            DB::beginTransaction();
            $code = mt_rand(100000,999999);
            Code::create([
                'email' => $freelancer->email,
                'code' => $code
            ]);
            // Mail::to($freelancer)->send(new codeMail($code));
            DB::commit();
            return $code;
            return response()->json([
                'messaage' => 'the code has been sent'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

}
?>