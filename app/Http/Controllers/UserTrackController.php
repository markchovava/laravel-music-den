<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserTrackResource;
use App\Models\UserTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserTrackController extends Controller
{
    
    public function index(Request $request){
        if(!empty($request->search)){
            $data = UserTrack::with(['user'])->where('name', 'LIKE', '%' . $request->search . '%')->paginate(12);
            Log::info($data);
            return UserTrackResource::collection($data);
            
        }
        $data = UserTrack::with(['user'])->paginate(12);
        return UserTrackResource::collection($data);
    }

    public function indexByAuth(){
        $user_id = Auth::user()->id;
        $data = UserTrack::with(['user'])->where('user_id', $user_id)->paginate(12); 
        return UserTrackResource::collection($data);
    }

    public function store(Request $request){
        $user_id = Auth::user()->id;
        $track = UserTrack::where('name', 'LIKE', $request->name)
                 ->where('user_id', $user_id)->first();
        if(isset($track)){
            if(!empty($request->image)){ $track->image = $request->image; }
            if(!empty($request->album)){ $track->album = $request->album; }
            if(!empty($request->tags)){ $track->tags = $request->tags; }
            $track->updated_at = now();
            $track->save();
            return response()->json([
                'data' => new UserTrackResource($track),
                'message' => 'Already liked.',
            ]); 
        }
        $data = new UserTrack();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->artist = $request->artist;
        if(!empty($request->image)) { $data->image = $request->image; }
        if(!empty($request->album)){ $data->album = $request->album; }
        if(!empty($request->tags)){ $data->tags = $request->tags; }
        $data->updated_at = now();
        $data->created_at = now();
        $data->save();
        return response()->json([
            'data' => new UserTrackResource($data),
            'message' => 'Liked successfully.'
        ]);

    }


    public function delete($id){
        $data = UserTrack::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Unliked Successfully.'
        ]);
    }
    
}
