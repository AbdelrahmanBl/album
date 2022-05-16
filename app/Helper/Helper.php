<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Helper extends Model
{
    static public function pictures_dir()
    {
        return [
            "public" => "public/pictures",
            "storage" => "storage/pictures",
        ];
    }

    static public function addFile($file) 
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniq = $name . auth()->user()->id . time();
        $dir = Helper::pictures_dir()['public'];
        $file_name = "{$uniq}.{$file->extension()}";
        $path = "{$dir}/$file_name";

        $file->storeAs($dir,$file_name);
        

        return [
            'path' => $file_name,
            'name' => $name
        ];
    }

    static public function copy($copy_prictures,$album_to_id)
    {
        $dir = Helper::pictures_dir()['public'];
        $pictures = [];
        foreach ($copy_prictures as $picture) {
            $uniq = $picture->name . auth()->user()->id . time();
            $extension = explode('.',$picture->path)[1];
            $new_path  = "{$dir}/{$uniq}.{$extension}";
            $new_file_path = "{$uniq}.{$extension}";
            Storage::copy("{$dir}/{$picture->path}", $new_path);
            
            $pictures[] = [
                "album_id" => $album_to_id,
                "name"     => $picture->name,
                "path"     => $new_file_path,
            ];
        }
        return $pictures;
    }

    static public function delete_album($album)
    {
        $dir = Helper::pictures_dir()['public'];
        $pictures = [];
        foreach ($album->pictures as $picture) {
            Helper::delete_file($picture->path);
        }
    }

    static public function delete_file($file_name)
    {
        $dir = Helper::pictures_dir()['public'];
        $path = "{$dir}/$file_name";
        if(Storage::exists($path))
            Storage::delete($path);
    }
}
