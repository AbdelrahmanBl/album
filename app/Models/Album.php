<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Album extends Model
{
    protected $fillable = [
        "user_id",
        "name",
    ];

    public function getFirstPictureAttribute() 
    {
        $firstPicPath = $this->pictures->first();
        if($firstPicPath && Storage::exists($firstPicPath->path))
            return asset(str_replace('public','storage',$firstPicPath->path));
        else return asset('not_found.png');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
