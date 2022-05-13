<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Picture extends Model
{
    public function getPicturePathAttribute() 
    {
        if(Storage::exists($this->path))
            return asset(str_replace('public','storage',$this->path));
        else return asset('not_found.png');
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
