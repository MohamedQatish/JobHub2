<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait UploadCvTrait{

    public function uploadCv(Request $request,$folderName){
        $cv = $request->file('cv')->getClientOriginalName();
        $request->file('cv')->storeAs($folderName,$cv,'freelancers');
        return $cv;
    }
}
?>