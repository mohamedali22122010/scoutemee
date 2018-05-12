<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests;
use Illuminate\Http\Request;
use File;
use Storage;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Stores an uploaded file
     */
    protected function UploadFile(Request $request, $file,$path = '',$visibility = null)
    {
        if ($request->hasFile($file)) {
            if ($request->file($file)->isValid()) {
                $name = md5(Carbon::now()->toDateTimeString() . rand(1, 100) . '.' .$request->file($file)->getClientOriginalName()).".".$request->file($file)->getClientOriginalExtension();
                if($path){
                    $name = $path."/".$name;
                }
                if(app()->environment('local'))
                    $name = "testing/".$name;
                Storage::put($name,File::get($request->file($file)->getRealPath()),$visibility);
                return $name;
            }
        }
        return false;
    }
}
