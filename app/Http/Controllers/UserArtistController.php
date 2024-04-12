<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserArtistResource;
use App\Models\UserArtist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserArtistController extends Controller
{
    
    public function index(Request $request){
        if(!empty($request->search)){
            $data = UserArtist::with(['user'])->where('name', $request->search)->paginate(12);
        } else {
            $data = UserArtist::with(['user'])->paginate(12);
        }
        return UserArtistResource::collection($data);
    }

    public function indexByAuth(){
        $user_id = Auth::user()->id;
        $data = UserArtist::with(['user'])->where('user_id', $user_id)->paginate(12); 
        return UserArtist::collection($data);
    }

    public function store(Request $request){
        $user_id = Auth::user()->id;
        $artist = UserArtist::where('name', 'LIKE', $request->name)
                 ->where('user_id', $user_id)->first();
        if(isset($artist)){
            if(!empty($request->mbid)){ $artist->mbid = $request->mbid; }
            if(!empty($request->image)){ $artist->image = $request->image; }
            if(!empty($request->description)){ $artist->description = $request->description; }
            return response()->json([
                'data' => new UserArtistResource($artist),
                'message' => 'Already liked.',
            ]); 
        }
        $data = new UserArtist();
        $data->user_id = $user_id;
        if(!empty($request->name)){ $data->name = $request->name; }
        if(!empty($request->mbid)){ $data->mbid = $request->mbid; }
        if(!empty($request->image)){ $data->image = $request->image; }
        if(!empty($request->description)){ $data->description = $request->description; }
        $data->save();
        return response()->json([
            'data' => $data,
            'message' => 'Liked successfully.'
        ]);

    }


    public function delete($id){
        $data = UserArtist::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Unliked Successfully.'
        ]);
    }
    
}
