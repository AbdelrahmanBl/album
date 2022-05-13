<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Helper extends Model
{
    static public function pictures_dir()
    {
        return "public/pictures";
    }

    static public function addFile($file) 
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniq = auth()->user()->id . '-' . $name . time();
        $dir = Helper::pictures_dir();
        $file_name = "{$uniq}.{$file->extension()}";
        $path = "{$dir}/$file_name";

        $file->storeAs($dir,$file_name);
        

        return [
            'path' => $path,
            'name' => $name
        ];
    }

    static public function copy($copy_prictures,$album_to_id)
    {
        $dir = Helper::pictures_dir();
        $pictures = [];
        foreach ($copy_prictures as $picture) {
            $uniq = auth()->user()->id . '-' . $picture->name . time();
            $extension = explode('.',$picture->path)[1];
            $new_path  = "{$dir}/{$uniq}.{$extension}";
            Storage::copy($picture->path, $new_path);
            
            $pictures[] = [
                "album_id" => $album_to_id,
                "name"     => $picture->name,
                "path"     => $new_path,
            ];
        }
        return $pictures;
    }

    static public function delete_file($path)
    {
        if(Storage::exists($path))
            Storage::delete($path);
    }
}
