<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => "Error {$validator->errors()}"
            ]);
        }

        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json([
                'status' => true, 
                'res'=> $this->respondWithToken($token),
            ],200); 
        }else{
            return response()->json([
                'Error' => true,
                'Message' => "CRENECIALES INCORRETAS: email o password incorrecto",
                'errors' => $validator->errors()
            ], 401);
        }

    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => Auth::user() ,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}