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
            $track->image = $request->image;
            $track->album = !empty($request->album) ? $request->album : '';
            $track->tags = !empty($request->tags) ? $request->tags : '';
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
        $data->image = $request->image;
        $data->album = !empty($request->album) ? $request->album : '';
        $data->tags = !empty($request->tags) ? $request->tags : '';
        $data->updated_at = now();
        $data->created_at = now();
        $data->save();
        return response()->json([
            'data' => new UserTrackResource($data),
            'message' => 'Saved successfully.'
        ]);

    }


    public function delete($id){
        $data = UserTrack::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Deleted successfully.'
        ]);
    }
    
}
