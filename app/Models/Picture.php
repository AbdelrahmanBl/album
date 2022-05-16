<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use App\Helper\Helper;

class Picture extends Model
{
    public function getPicturePathAttribute() 
    {
        $public_path  = Helper::pictures_dir()['public'] . '/' . $this->path;
        $storage_path = Helper::pictures_dir()['storage'] . '/' . $this->path;
        if(Storage::exists($public_path))
            return asset($storage_path);
        else return asset('not_found.png');
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
