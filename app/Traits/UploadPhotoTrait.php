<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait UploadPhotoTrait{

    public function uploadPhoto(Request $request,$folderName,$disks){
        $photo = $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs($folderName,$photo,$disks);
        return $photo;
    }
}
?>