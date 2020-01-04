<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            array(
                'status' => 200,
                'data' => User::where('estado', '!=',3)->get(),
                'token' => auth()->refresh()
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $token = auth()->refresh();
        $json = json_decode($request->json, JSON_FORCE_OBJECT);
        try {
            $json['nombres'] = mb_strtoupper($json['nombres']);
            $json['apellidos'] = mb_strtoupper($json['apellidos']);
            if ($request->hasFile('src_img')) {
                $name = $request->file('src_img')->store('/');
                Storage::move($name, 'public/avatars/'.$name);
                $json['src_img'] = $name;
            }
            $json['password'] = Hash::make($json['password']);
            $json['created_at'] = Carbon::now();
            $json['updated_at'] = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => User::create($json),
                'token' => $token
            );
        } catch (\Exception $ex) {
            $data = array(
                'status' => 403,
                'data' => $ex->getMessage(),
                'token' => $token
            );
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $token = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $usuario->dni = $json->dni;
            $usuario->nombres = mb_strtoupper($json->nombres);
            $usuario->apellidos = mb_strtoupper($json->apellidos);
            if ($request->hasFile('src_img')) {
                if ($usuario->src_img) {
                    Storage::delete('public/avatars/'.$usuario->src_img);
                }
                $name = $request->file('src_img')->store('/');
                Storage::move($name, 'public/avatars/'.$name);
                $usuario->src_img = $name;
            }
            $usuario->email = $json->email;
            if (isset($json->password)) {
                $usuario->password = Hash::make($json->password);
            }
            $usuario->perfil = $json->perfil;
            $usuario->estado = $json->estado;
            $usuario->updated_at = Carbon::now();
            $usuario->save();
            $data = array(
                'status' => 201,
                'data' => $usuario,
                'token' => $token
            );
        } catch (\Exception $ex) {
            $data = array(
                'status' => 403,
                'data' => $ex->getMessage(),
                'token' => $token
            );
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        //
    }
}
