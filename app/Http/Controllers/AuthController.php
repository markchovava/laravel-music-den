<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request){
        
        $user = User::where('email', $request->email)->first();
        if(!isset($user)){
            return response()->json([
                'email_message' => 'Email is not found.',
                'error' => 401,
                'status' => 0,
            ]);
        }
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'password_message' => 'Password is incorrect.',
                'error' => 401,
                'status' => 1,
            ]);
        }

        $response = response()->json([
            'message' => 'Login Successfully.',
            'auth_token' => $user->createToken($user->email)->plainTextToken,
            'role_level' => !empty($user->role_level) ? $user->role_level : 1,
            'status' => 2,
        ]);

        return $response;
   
    }
   
    public function register(Request $request){
        $user = User::where('email', $request->email)->first();
        if(isset($user)){
            return response()->json([
                'email_message' => 'Email is already registered, please login.',
                'status' => 0,
            ]);
        }
        $data = new User();
        $data->role_level = 1;
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->name = $data->first_name . ' ' . $data->last_name;
        $data->is_agree = $request->is_agree;
        $data->email = $request->email;
        $data->code = $request->password;
        $data->created_by_social = !empty($request->created_by_social) ? $request->created_by_social : 0;
        $data->password = Hash::make($request->password);
        $data->save();

        return response()->json([
            'message' => 'Created Successfully.',
            'status' => 1,
        ]);
    }
    
}
