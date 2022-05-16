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
        $firstPic = $this->pictures->first();
        if($firstPic)
            return $firstPic->picture_path;
        else return asset('not_found.png');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
