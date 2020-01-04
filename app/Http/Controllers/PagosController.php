<?php

namespace App\Http\Controllers;

use App\Ciclos;
use App\Matriculas;
use App\Pagos;
use App\TipoPagos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PagosController extends Controller
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
                'data' => Pagos::all(),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pagos  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pagos $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pagos  $pago
     * @return \Illuminate\Http\Response
     */
    public function edit(Pagos $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pagos  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagos $pago)
    {
        $token = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $pago->matriculas_id = $json->matriculas_id;
            $pago->tipo_pagos_id = $json->tipo_pagos_id;
            $pago->numero_vaucher = $json->numero_vaucher;
            $pago->fecha_vaucher = $json->fecha_vaucher ? Carbon::parse($json->fecha_vaucher) : null;
            $pago->importe = $json->importe;
            if ($request->hasFile('src_img')) {
                if ($pago->src_img) {
                    Storage::delete('public/vauchers/'.$pago->src_img);
                }
                $name = $request->file('src_img')->store('/');
                Storage::move($name, 'public/vauchers/'.$name);
                $pago->src_img = $name;
            }
            $pago->observacion = $json->observacion;
            $pago->estado = $json->estado;
            $pago->updated_at = Carbon::now();
            $pago->save();
            $matricula = Matriculas::find($pago->matriculas_id);
            if ( DB::table('pagos')->where('matriculas_id', $matricula->id)->count() > 1 ) {
                $obs = DB::table('pagos')->orWhere('estado', 0)->orWhere('estado', 3)->count();
                $pro = DB::table('pagos')->where('estado', 2)->count();
                if ( $obs > 0 ) {
                    $matricula->estado = 3;
                } else if ( $pro > 0 ) {
                    $matricula->estado = 2;
                } else {
                    $matricula->estado = 1;
                }
            } else {
                $matricula->estado = $pago->estado;
            }
            $data = array(
                'status' => 201,
                'data' => $matricula->save(),
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
     * @param  \App\Pagos  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagos $pago)
    {
        //
    }

    /**
     * @param Matriculas $matricula
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexByMatricula(Matriculas $matricula)
    {
        $pagos = DB::table('pagos')->where('matriculas_id', $matricula->id)->get();
        foreach ($pagos as $val)
        {
            $val->matriculas = Matriculas::find($val->matriculas_id);
            $val->matriculas->ciclos = Ciclos::find($val->matriculas->ciclos_id);
            $val->tipo_pagos = TipoPagos::find($val->tipo_pagos_id);
        }
        return response()->json(
            array(
                'status' => 200,
                'data' => $pagos,
                'token' => auth()->refresh()
            )
        );
    }
}
