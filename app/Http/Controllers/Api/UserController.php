<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();


        return response()->json([
            "status" => 1,
            "msg" => "¡Registro de usuario exitoso!",
        ]);
    }


    public function login(Request $request) {


        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);


        $user = User::where("email", "=", $request->email)->first();

        if( isset($user->id) ){
            if(Hash::check($request->password, $user->password)){
                //creamos el token
                $token = $user->createToken("auth_token")->plainTextToken;
                //si está todo ok
                return response()->json([
                    "status" => 1,
                    "message" => "¡Usuario logueado exitosamente!",
                    "accessToken" => $token
                ]);
            }else{
                return response()->json([
                    "status" => 0,
                    "message" => "La password es incorrecta",
                ], 404);
            }

        }else{
            return response()->json([
                "status" => 0,
                "message" => "Usuario no registrado",
            ], 404);
        }
    }

    public function userProfile() {
        return response()->json([
            "status" => 0,
            "message del perfil de usuario",
            "data" => auth()->user()
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 1,
            "message" => "Cierre de Sesión",
        ]);
    }
}
