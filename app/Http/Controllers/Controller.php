<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // IMAGES 
    
    // Upload Images
    public function upload(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'),$picName );
        return $picName;
    }


    // Handle deletion of image from server for add/edit category
    public function deleteImage(Request $request){
        $fileName = $request->imageName; 
        $this->deleteFileFromServer($fileName);
        return $fileName.' deleted';
    }

    
    public function deleteFileFromServer($fileName){
        $filePath = public_path().$fileName;
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }
}
