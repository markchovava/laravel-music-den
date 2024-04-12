<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function generateRandomText($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }


    public function index(Request $request){
        if(!empty($request->search)){
            $data = User::where('name', 'LIKE', '%' . $request->search . '%')->paginate(12);
        } else{
            $data = User::orderBy('name', 'asc')->orderBy('created_at', 'desc')->paginate(12);
        }
        return UserResource::collection($data);
    }

    public function view($id){
        $data = User::find($id);
        return response()->json([
            'data' => new UserResource($data),
        ]);
    }

    public function store(Request $request){
        $data = new User();
        $data->role_level = $request->role_level;
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->name = $data->first_name . ' ' . $data->last_name;
        $data->is_agree = $request->is_agree;
        $data->dob = $request->dob;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->created_by_social = 0;
        $data->code = $this->generateRandomText();
        $data->password = Hash::make($data->code);
        $data->save();

        return response()->json([
            'message' => 'Saved successfully.',
            'data' => new UserResource($data),
        ]);
    }

    public function update(Request $request, $id){
        $data = User::find($id);
        $data->role_level = $request->role_level;
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->name = $data->first_name . ' ' . $data->last_name;
        $data->is_agree = $request->is_agree;
        $data->dob = $request->dob;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->created_by_social = 0;
        $data->save();

        return response()->json([
            'message' => 'Saved successfully.',
            'data' => new UserResource($data),
        ]);
    }


    public function delete($id){
        $data = User::find($id);
        $data->delete();

        return response()->json([
            'message' => 'Deleted successfully.',
        ]);
    }

    
}
