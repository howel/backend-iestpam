<?php

namespace App\Http\Controllers;

use App\TipoMatriculas;
use App\TipoPagos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPagosController extends Controller
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
                'data' => TipoPagos::all(),
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
            $json['created_at'] = Carbon::now();
            $json['updated_at'] = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => TipoPagos::create($json),
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
     * @param  \App\TipoPagos  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function show(TipoPagos $tipoPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoPagos  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoPagos $tipoPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoPagos  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoPagos $tipoPago)
    {
        $auth = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $tipoPago->tipo_matriculas_id = $json->tipo_matriculas_id;
            $tipoPago->nombre = mb_strtoupper($json->nombre);
            $tipoPago->importe = $json->importe;
            $tipoPago->partes = $json->partes;
            $tipoPago->estado = $json->estado;
            $tipoPago->updated_at = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => $tipoPago->save(),
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
     * @param  \App\TipoPagos  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoPagos $tipoPago)
    {
        //
    }

    /**
     * Others
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexByTipoMatricula(TipoMatriculas $tipoMatricula)
    {
        $tipoPagos = DB::table('tipo_pagos')->where('tipo_matriculas_id', $tipoMatricula->id)->get();
        foreach ($tipoPagos as $val)
        {
            $val->tipo_matriculas = TipoMatriculas::find($val->tipo_matriculas_id);
        }
        return response()->json(
            array(
                'status' => 200,
                'data' => $tipoPagos,
                'token' => auth()->refresh()
            )
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        return response()->json(
            array(
                'status' => 200,
                'data' => DB::table('tipo_pagos')->where('estado', 1)->get(),
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
                'data' => DB::table('tipo_pagos')->where('estado', 1)->get()
            )
        );
    }
}
