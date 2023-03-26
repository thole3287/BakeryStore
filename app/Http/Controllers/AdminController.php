<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
class AdminController extends Controller
{
    // public function login(Request $req)
    // {
    //     $val= $req->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:6|max:20'
    //     ], [
    //         'email.required' => 'Please enter an email address',
    //         'email.email' => 'Please enter a valid email address',
    //         'password.required' => 'Please enter a password',
    //         'password.min' => 'The password must be at least 6 characters',
    //         'password.max' => 'The password may not be greater than 20 characters',
    //     ]);

    //     $authentication = array('email' => $val['email'], 'password' => $val['password']);

    //     if (Auth::attempt($authentication)) {
    //         $user = Auth::user();
    //         $token = $user->createToken('authToken')->accessToken;
    //         return response()->json(['token' => $token]);
    //     } else {
    //         return response()->json(['error' => 'Invalid credentials'], 401);
    //     }
    // }

    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (!$token = Auth::guard('api')->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     $user = Auth::guard('api')->user();

    //     if ($user->level != 1) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => JWTAuth::factory()->getTTL() * 60,
    //     ]);
    // }

    //oke
    public function userProfile() {
        return response()->json(auth()->user());
    }
    function identifyUser(Request $request) {
        $credentials = $request->only('email', 'password');
      
        if (Auth::attempt($credentials)) {
          $user = User::where('email', $request->email)->first();
      
          if ($user->level == 1) {
            $token = JWTAuth::fromUser($user);
      
            return response()->json([
              'success' => 'Admin login successful!!!',
              'token' => $token,
              'token_type' => 'bearer',
            ]);
          } else {
            return response()->json([
              'success' => 'Admin login failed!!!',
              'message' => 'Invalid user level'
            ]);
          }
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
          ]);
        }
      }
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (! $token = JWTAuth::attempt($credentials)) {
    //         return response()->json(['error' => 'invalid_credentials'], 401);
    //     }

    //     $user = Auth::user();

    //     if ($user->level !== 1) {
    //         return response()->json(['error' => 'unauthorized'], 401);
    //     }

    //     return response()->json(compact('token'));
    // }


}
