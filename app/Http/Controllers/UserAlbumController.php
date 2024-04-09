<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAlbumResource;
use App\Models\UserAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserAlbumController extends Controller
{
    public function index(Request $request){
        if(!empty($request->search)){
            $data = UserAlbum::with(['user'])->where('name', 'LIKE', '%' . $request->search . '%')->paginate(12);
            return UserAlbumResource::collection($data);
        } 
        $data = UserAlbum::with(['user'])->paginate(12);
        return UserAlbumResource::collection($data);
    }

    public function indexByAuth(){
        $user_id = Auth::user()->id;
        $data = UserAlbum::with(['user'])->where('user_id', $user_id)->paginate(12); 
        return UserAlbumResource::collection($data);
    }

    public function store(Request $request){
        $user_id = Auth::user()->id;
        $album = UserAlbum::where('name', 'LIKE', $request->name)
                 ->where('user_id', $user_id)->first();
        if(isset($album)){
            return response()->json([
                'data' => new UserAlbumResource($album),
                'message' => 'Already liked.',
            ]); 
        }
        $data = new UserAlbum();
        $data->user_id = Auth::user()->id;
        $data->name = $request->name;
        $data->artist = $request->artist;
        $data->image = $request->image;
        $data->save();
        return response()->json([
            'data' =>new UserAlbumResource($data),
            'message' => 'Saved successfully.'
        ]);

    }


    public function delete($id){
        $data = UserAlbum::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Deleted successfully.'
        ]);
    }


}
