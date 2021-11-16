<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AdminActions;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use AdminActions;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();
        $users = User::orderBy('id')
        ->first_name(request()->get('nombre'))
        ->last_name(request()->get('nombre'))
        ->paginate(5);
        return response()->json([
            'ok' => true,
            'usuarios' => $users
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required','unique:users'],
            'password' => ['required','min:5'],
            'role' => 'in:'. User::ADMIN_ROLE.','. User::USER_ROLE,
        ], [
            'fist_name.required' => 'El nombre de usuario es requerido',
            'last_name.required' => 'El apellido de usuario es requerido',
            'email.required' =>'El correo es requerido',
            'email.unique' => 'El correo ya ha sido registrado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe ser mayor a 4',
            'role.in' => 'El role debe ser '.User::ADMIN_ROLE.' o '.User::USER_ROLE
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear usuario',
                'errors' => $validator->errors()
            ],401);
        }

        if (!$request->role) {
            $request['role'] = User::USER_ROLE;
        }else{
            $campos['role'] = $request->role;
        }

        $request['password'] = Hash::make($request->password);

        $usuario = User::create($request->all());
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'ok' => true,
            'usuario' => $usuario,
            'token' => $token
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {        
        $data = $request->all();

        if ($request->has('password')) {
            if (Hash::check($request->old_password, $user->password)) {
                $data['password'] = Hash::make($request->password);
            }else{
                return response()->json([
                    'ok' => false,
                    'message' => 'La contraseña no coincide con la anterior'
                ],400);
            }
        }

        $user->update($data);
        $user = User::findOrFail($user->id);

        return response()->json([
            'ok' => true,
            'usuario' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'ok' => true,
            'usuario' => $user
        ]); 
    }
}
