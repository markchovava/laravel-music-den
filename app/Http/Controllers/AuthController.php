<?php

namespace App\Http\Controllers;



use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function generateRandomText($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function view(){
        $data = Auth::user();
        return response()->json([
            'data' => new UserResource($data),
        ]);
    }

    public function update(Request $request){
        $user_id = Auth::user()->id;
        $data = User::find($user_id);
        if(!empty($request->email)) { $data->email = $request->email; }
        if(!empty($request->last_name)) { $data->first_name = $request->first_name; }
        if(!empty($request->first_name)) { $data->first_name = $request->first_name; }
        if(!empty($request->dob)) { $data->dob = $request->dob; }
        if(!empty($request->gender)) {$data->gender = $request->gender; }
        $data->save();
        return response()->json([
            'data' => new UserResource($data),
        ]);
    }

    public function password(Request $request){
        $user_id = Auth::user()->id;
        $data = User::find($user_id);
        if(!empty($request->password)) { 
            $code = $request->password;
            $data->password = Hash::make($code); 
            $data->code = $code;
            $data->save();
            return response()->json([
                'data' => new UserResource($data),
                'message' => 'Password is saved successfully.',
            ]);
        } 

        return response()->json([
            'data' => new UserResource($data),
            'message' => 'Password not saved.',
        ]);


    }

    public function loginByGmail(Request $request){
        $user = User::where('email', $request->email)->first();
        if(!isset($user)){
            return response()->json([
                'gmail_message' => 'Email is not registered. Please Create an account.',
                'error' => 401,
                'status' => 0,
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
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'message' => 'Created Successfully.',
            'status' => 1,
        ]);
    }
    public function registerByGmail(Request $request){
        $user = User::where('email', $request->email)->first();
        if(isset($user)){
            return response()->json([
                'email_message' => 'Email is already registered, please login.',
                'status' => 0,
            ]);
        }
        $data = new User();
        $data->role_level = 1;
        $data->email = $request->email;
        $data->created_by_social = 1;
        $data->code = $this->generateRandomText();
        $data->password = Hash::make($data->code);
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'message' => 'Created Successfully.',
            'status' => 1,
        ]);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'You have been succesfully logged out.',
        ]);
    }
    
}
