<?php

namespace App\Http\Controllers;

use App\Carreras;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarrerasController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('activeExToken');
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
                'data' => Carreras::all(),
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
        $auth = auth()->refresh();
        $json = json_decode($request->json, JSON_FORCE_OBJECT);
        try {
            $json['nombre'] = mb_strtoupper($json['nombre']);
            $json['descripcion'] = mb_strtoupper($json['descripcion']);
            if ($request->hasFile('src_img')) {
                $name = $request->file('src_img')->store('/');
                Storage::move($name, 'public/careers/'.$name);
                $json['src_img'] = $name;
            }
            $json['created_at'] = Carbon::now();
            $json['updated_at'] = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => Carreras::create($json),
                'token' => $auth
            );
        } catch (\Exception $ex) {
            $data = array(
                'status' => 403,
                'data' => $ex->getMessage(),
                'token' => $auth
            );
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Carreras  $carrera
     * @return \Illuminate\Http\Response
     */
    public function show(Carreras $carrera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Carreras  $carrera
     * @return \Illuminate\Http\Response
     */
    public function edit(Carreras $carrera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Carreras  $carrera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carreras $carrera)
    {
        $auth = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $carrera->nombre = mb_strtoupper($json->nombre);
            $carrera->descripcion = mb_strtoupper($json->descripcion);
            if ($request->hasFile('src_img')) {
                $name = $request->file('src_img')->store('/');
                Storage::move($name, 'public/careers/'.$name);
                $carrera->src_img = $name;
            }
            $carrera->estado = $json->estado;
            $carrera->updated_at = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => $carrera->save(),
                'token' => $auth
            );
        } catch (\Exception $ex) {
            $data = array(
                'status' => 403,
                'data' => $ex->getMessage(),
                'token' => $auth
            );
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Carreras  $carrera
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carreras $carrera)
    {
        //
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        return response()->json(
            array(
                'status' => 200,
                'data' => Carreras::where('estado', 1)->get(),
                'token' => auth()->refresh()
            )
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeExToken()
    {
        return response()->json(
            array(
                'status' => 200,
                'data' => Carreras::where('estado', 1)->get()
            )
        );
    }
}
