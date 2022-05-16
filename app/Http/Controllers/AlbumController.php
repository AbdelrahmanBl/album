<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Picture;
use App\Helper\Helper;
use Validator;
use Exception;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create() 
    {
       return view('album.create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:25'
        ]);
        
        if($validate->fails())
            return response()->json([
                'is_error' => true,
                'message' => $validate->messages()
            ]);
        try {
            $album = new Album();
            $album->user_id = auth()->user()->id;
            $album->name    = $request->name;
            $album->save();
            
            $paths = $request->paths ?? [];
            $pictures = [];
            foreach($paths as $path) {
                $pictures[] = [
                    "album_id" => $album->id,
                    "name"     => $path["name"],
                    "path"     => $path["path"],
                ];
            }
            if(count($pictures) > 0)
                Picture::insert($pictures);

            return response()->json([
                'is_error' => false,
                'url' => route('home')
            ]);
        }catch(Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => $e->getMessage()
            ]);
        }   
    }

    public function edit($id)
    {
        $album = Album::where('user_id',auth()->user()->id)->findOrFail((int) $id);

        return view('album.edit', compact('album'));
    }

    public function update(Request $request,$id)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:25'
        ]);
        
        if($validate->fails())
            return response()->json([
                'is_error' => true,
                'message' => $validate->messages()
            ]);
        try {
            $album = Album::where('user_id',auth()->user()->id)->findOrFail((int) $id);
            $album->name = $request->name;
            $album->save();
            
            $paths = $request->paths ?? [];
            $pictures = [];
            foreach($paths as $path) {
                $pictures[] = [
                    "album_id" => $album->id,
                    "name"     => $path["name"],
                    "path"     => $path["path"],
                ];
            }
            if(count($pictures) > 0)
                Picture::insert($pictures);

            return response()->json([
                'is_error' => false,
                'url' => route('home')
            ]);
        }catch(Exception $e) {
            return response()->json([
                'is_error' => true,
                'url' => $e->getMessage()
            ]);
        }   
    }

    public function destroy(Request $request,$id)
    {
        $album = Album::where('user_id',auth()->user()->id)->findOrFail((int) $id);

        
        foreach($album->pictures as $picture) {
            Helper::delete_file($picture->path);
        }
        
        if($request->type == 'all')
            $album->delete();
            
        Picture::whereIn('id',$album->pictures->pluck('id'))->delete();

        return redirect()->back();
    }

    public function upload(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'file'      => 'required|image|max:2000'
        ]);
        if($validate->fails())
            return response()->json(['path' => []]);

        $file = $request->file('file');
        $path = Helper::addFile($file);
        $data = [
            'path' => $path
        ];
        return response()->json($data);
    }

    public function move(Request $request, $album_id) 
    {
        $album = Album::where('user_id',auth()->user()->id)->findOrFail((int) $album_id);
        
        $pictures = Helper::copy($album->pictures,$request->move_to_id);
        Helper::delete_album($album);
        Picture::whereIn('id',$album->pictures->pluck('id'))->delete();
        
        if(count($pictures) > 0)
                Picture::insert($pictures);

        return redirect(route('home'));
    }
}
