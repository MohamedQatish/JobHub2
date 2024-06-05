<?php

namespace App\Http\Controllers;

use App\Mail\codeMail;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    //

    public function sendCode(){
        try{
            DB::beginTransaction();
            $user = Auth::user();
            $code = mt_rand(100000,999999);
            Code::create([
                'email' => $user->email,
                'code' => $code
            ]);
            Mail::to($user)->send(new codeMail($code));
            DB::commit();
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

    public function checkCode(Request $request){
        $validated = $request->validate([
            'code' => ['required','numeric','min:6','exists:codes,code']
        ]);
        $user = Auth::user();
        $code = Code::where('code',$validated['code'])->where('email',$user->email)->first();
        if($code->created_at > now()->addHour()){
            $code->delete();
            return response()->json([
                'message' => 'the code is outdated'
            ]);
        }
        $user->verified_at = now();
        $user->save();
        return response()->json([
            'message' => 'your email is verified'
        ]);
    }
}
