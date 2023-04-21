<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; 


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','send-password-reset-link']]);
    }

    public function changePassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // Get the user
        $user = $request->user();

        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['error' => 'Current password does not match.'], 400);
        }

        // Update the password
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Password updated successfully.'], 200);
    }


    // public function changePassword(Request $request)
    // {
    //     $user = Auth::user();
    //     $validator = Validator::make($request->all(), [
    //         'old_password' => 'required',
    //         'new_password' => 'required|string|min:6|confirmed',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     if (!Hash::check($request->old_password, $user->password)) {
    //         return response()->json(['error' => 'Invalid old password'], 401);
    //     }

    //     $user->password = Hash::make($request->new_password);
    //     $user->save();

    //     return response()->json(['success' => 'Password updated successfully'], 200);
    // }
    public function loginAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            if ($user->level == 2) {
                return response()->json([
                    'message' => 'Login successful as manager',
                    'token' => $token,
                    // 'user' => $user
                ], 200);
            } else if ($user->level == 1) {
                return response()->json([
                    'message' => 'Login successful as staff',
                    'token' => $token,
                    // 'user' => $user
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Invalid user level',
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate user input
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'phone' => 'required|numeric|max:10',
            'address' =>'required'
            // 'email' => 'required|email|unique:users,email,'.$id
        ],[
            'name.required' => 'Name is required!',
            'name.string' => 'Name must be a string!',
            'phone.required' => 'Phone is required!',
            'phone.numeric' => 'Phone must be numeric!',
            'phone.max' => 'Phone number can have maximum 10 digits!',
            'address.required' => 'Address is required!'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();

        return response()->json(['user' => $user]);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ],[
            'name.required' => 'Name is required!',
            'name.string' => 'Name is string!',
            'name.between' => 'Name must be between 2 to 100 letters!',
            'email.required' => 'Email is required!',
            'email.string' => 'Email is string!',
            'email.email' => 'Email must be in the correct format!',
            'email.max' => 'Maximum email length is 100 letters',
            'email.unique' => 'This email already exists on the system!',
            'password.required' => 'Password is required!',
            'password.string' => 'Password is string!',
            'password.confirmed' => 'Confirm password is incorrect!',
            'password.min' => 'Minimum password length is 6 letters',
        ]);
        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            // 'user' => auth()->user()
        ]);
    }
}
