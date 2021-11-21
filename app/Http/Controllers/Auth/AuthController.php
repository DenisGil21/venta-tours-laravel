<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'ok' => false,
                'message' => 'Credenciales invalidas'
            ], 401);
        }

        $usuario = User::where('email',$request->email)->firstOrFail();
        $token = Auth::attempt($credentials);


        return response()->json([
            'ok' => true,
            'usuario' => $usuario,
            'token' => $token
        ], 201);

    }

    public function refresh(){
       
            $usuario = Auth::user();

            $token = Auth::getToken();
            try {
                $token = Auth::refresh($token);
                return response()->json([
                    'ok'=> true,
                    'token' => $token,
                    'usuario' => $usuario,
                ], 200);
            } catch (TokenExpiredException $e) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El token ha expirado'
                ], 401);
            } catch (TokenBlacklistedException $e) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El token ha cambiado, necesita volver a loguearse'
                ], 401);
            } catch (TokenInvalidException $e) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Token invalido'
                ], 401);
            }
    }

    
}
