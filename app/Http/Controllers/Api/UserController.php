<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class UserController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('user_index'), 403);
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

       /* $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();*/
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response()->json([
            "success" => true,
            "message" => "¡Registro de usuario exitoso!",
            'user' => $user,
            'token' => $token,
            'type' => 'bearer',
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

                $credentials = $request->only('email', 'password');

                $token = auth()->claims(
                    [
                        'nombres'   => $user->nombres,
                        'apellidos' => $user->apellidos,
                        'documento' => $user->documento,
                        'email'     => $user->email,
                        'telefono'  => $user->telefono,
                        'roles'     => $user->roles->pluck('name') ?? [],
                        'permisos'  => $user->getPermissionsViaRoles()->pluck('name') ?? [],
                    ]
                    )->attempt($credentials);


                if (!$token) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 401);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Usuario logueado exitosamente',
                    //'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'bearer'
                    ]);
            }else{
                return response()->json([
                    "success" => false,
                    "message" => "El password es incorrecto",
                ], 404);
            }

        }else{
            return response()->json([
                "success" => false,
                "message" => "Usuario no registrado",
            ], 404);
        }

        /*$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'success' => true,
                'message' => 'Usuario logueado exitosamente',
                'user' => $user,
                'token' => $token,
                'type' => 'bearer'

            ]);*/
    }

    public function userProfile() {
        return response()->json([
            "success" => true,
            "message" => "datos del usuario",
            "data"  => auth()->user(),
            //"roles" => auth()->user()->roles->pluck('name') ?? [],
            "permisos" => auth()->user()->getPermissionsViaRoles()->pluck('name') ?? [],
            //"permisos"   =>  auth()->user()->permissions->pluck('name') ?? []
            //"rolesl_all" => User::has('roles')
        ]);
    }

    public function logout() {

        Auth::logout();
        return response()->json([
            'success' =>  true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'success'     => true,
            'acces_token' => Auth::refresh(),
            'token_type'  => 'bearer',

        ]);
    }
}
