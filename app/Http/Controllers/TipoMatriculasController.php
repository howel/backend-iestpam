<?php

namespace App\Http\Controllers;

use App\TipoMatriculas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoMatriculasController extends Controller
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
                'data' => TipoMatriculas::all(),
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
            $json['inicio'] = Carbon::parse($json['inicio']);
            $json['final'] = Carbon::parse($json['final']);
            $json['created_at'] = Carbon::now();
            $json['updated_at'] = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => TipoMatriculas::create($json),
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
     * @param  \App\TipoMatriculas  $tipoMatricula
     * @return \Illuminate\Http\Response
     */
    public function show(TipoMatriculas $tipoMatricula)
    {
        return response()->json(
            array(
                'status' => 200,
                'data' => $tipoMatricula,
                'token' => auth()->refresh()
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoMatriculas  $tipoMatricula
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMatriculas $tipoMatricula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoMatriculas  $tipoMatricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoMatriculas $tipoMatricula)
    {
        $auth = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $tipoMatricula->nombre = mb_strtoupper($json->nombre);
            $tipoMatricula->descripcion = mb_strtoupper($json->descripcion);
            $tipoMatricula->inicio = Carbon::parse($json->inicio);
            $tipoMatricula->final = Carbon::parse($json->final);
            $tipoMatricula->importe = $json->importe;
            $tipoMatricula->estado = $json->estado;
            $tipoMatricula->updated_at = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => $tipoMatricula->save(),
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
     * @param  \App\TipoMatriculas  $tipoMatricula
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoMatriculas $tipoMatricula)
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
                'data' => DB::table('tipo_matriculas')->where('estado', 1)->get(),
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
                'data' => DB::table('tipo_matriculas')->where('estado', 1)->get()
            )
        );
    }
}
