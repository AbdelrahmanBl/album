<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Picture;
use App\Helper\Helper;

class PictureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy($id)
    {
        $picture = Picture::whereHas('album',function($album) {
                                        $album->where('user_id',auth()->user()->id);
                                    })->findOrFail((int) $id);
        Helper::delete_file($picture->path);
        $picture->delete();

        return redirect()->back();
    }
}
