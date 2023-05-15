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
        /*abort_if(Gate::denies('user_index'), 403);
        $users = User::paginate(5);
        return view('users.index', compact('users'));*/
    }

    public function register(Request $request) {
        $request->validate([
            'nombres'     => 'required|string|min:6',
            'apellidos'   => 'required|string|min:6',
            'email'       => 'required|email|unique:users',
            'documento'   => 'required|integer|unique:users',
            'password'    => 'required|string|min:6'
        ]);

       /* $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();*/
        $user = User::create([
            'nombres'    => $request->nombres,
            'apellidos'  => $request->apellidos,
            'documento'  => $request->documento,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response()->json([
            "success"     => true,
            "message"     => "¡Registro de usuario exitoso!",
            'user'        => $user,
            'accessToken' => $token,
            'type'        => 'bearer',
        ]);
    }


    public function login(Request $request) {


        $request->validate([
            "email"    => "required|email",
            "password" => "required"
        ]);


        $user = User::where("email", "=", $request->email)->first();

        if( isset($user->id) ){
            if(Hash::check($request->password, $user->password)){

                $credentials = $request->only('email', 'password');

                /*$token = auth()->claims(
                    [
                        'nombres'   => $user->nombres,
                        'apellidos' => $user->apellidos,
                        'documento' => $user->documento,
                        'email'     => $user->email,
                        'telefono'  => $user->telefono,
                        'roles'     => $user->roles->pluck('name') ?? [],
                        'permisos'  => $user->getPermissionsViaRoles()->pluck('name') ?? [],
                    ]
                    )->attempt($credentials);*/
                $token = auth()->attempt($credentials);

                if (!$token) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 401);
                }

                $dataUser = [
                    'nombres'   => $user->nombres,
                    'apellidos' => $user->apellidos,
                    'documento' => $user->documento,
                    'email'     => $user->email,
                    'telefono'  => $user->telefono,
                    'roles'     => $user->roles->pluck('name') ?? [],
                    'permisos'  => $user->getPermissionsViaRoles()->pluck('name') ?? [],
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Usuario logueado exitosamente',
                    'user'    => $dataUser,
                    'accessToken' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,
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

        $user = auth()->user();

        $dataUser = [
            'nombres'   => $user->nombres,
            'apellidos' => $user->apellidos,
            'documento' => $user->documento,
            'email'     => $user->email,
            'telefono'  => $user->telefono,
            'roles'     => $user->roles->pluck('name') ?? [],
            'permisos'  => $user->getPermissionsViaRoles()->pluck('name') ?? [],
        ];

        return response()->json([
            "success" => true,
            "message" => "datos del usuario",
            "user"    =>  $dataUser
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
        //$newToken = auth()->refresh();

        // Pass true as the first param to force the token to be blacklisted "forever".
        // The second parameter will reset the claims for the new token
        $newToken = Auth::refresh(true, true);
        return response()->json([
            'success'     => true,
            'accessToken' => $newToken,
            'token_type'  => 'bearer',

        ]);
    }
}
