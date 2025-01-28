<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public static function uploadFile($file,$path,$name=null) {
        if($name == null) {
            $name = rand(100,999).time().rand(100,999);
        }
        $originalFileName = $file->getClientOriginalName();
        $name = $name . '.' . $file->getClientOriginalExtension();

        //check if end of path has '/'
        if(!str_ends_with($path, '/')) {
            $path = $path . '/';
        }

        Storage::put($path . $name, file_get_contents($file));
        return [
            'path' => 'storage/'.$path . $name,
            'name' => $originalFileName
        ];
    }
}
