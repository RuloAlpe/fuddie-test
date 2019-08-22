<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($api_token)
    {
        if($user = User::usuarioValido($api_token)){
            return response()->json($user);

        }
        return response()->json([
            'status' => 'error',
            'message' => 'Usuario no encontrado'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($api_token)
    {
        $user = User::usuarioValido($api_token);
        if($user){
            if(User::hasAccount($user->id)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Este usuario tiene vinculada por lo menos una cuenta'
                ]);
            }

            if(User::deleteUser($user->id)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'El usuario ha sido eliminado correctamnete'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error, intentelo mas tarde'
            ]);
        }
    }

    public function accounts_user($api_token){
        $user = DB::table('users')->where('api_token', $api_token)->first();
        $accounts = DB::table('accounts')->where('id_user', $user->id)->get();

        return response()->json($accounts);
    }
}
