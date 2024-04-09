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
            return response()->json([
                'data' => new UserArtistResource($artist),
                'message' => 'Already liked.',
            ]); 
        }
        $data = new UserArtist();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->mbid = $request->mbid;
        $data->image = $request->image;
        $data->description = !empty($request->description) ? $request->description : '';
        $data->save();
        return response()->json([
            'data' => $data,
            'message' => 'Saved successfully.'
        ]);

    }


    public function delete($id){
        $data = UserArtist::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Deleted successfully.'
        ]);
    }
    
}
