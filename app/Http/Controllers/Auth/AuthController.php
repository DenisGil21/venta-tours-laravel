<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'ok' => false,
                'message' => 'Credenciales invalidas'
            ], 401);
        }

        $usuario = User::where('email',$request->email)->firstOrFail();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'ok' => true,
            'usuario' => $usuario,
            'token' => $token
        ], 201);

    }

    public function refresh(Request $request){
       
            $usuario = $request->user();

            $usuario->currentAccessToken()->delete();

            $token = $usuario->createToken('auth_token')->plainTextToken;

            return response()->json([
                'ok'=> true,
                'token' => $token,
                'usuario' => $usuario,
            ], 200);
    }

    
}
