<?php

namespace App\Http\Controllers;

use App\Periodos;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeriodosController extends Controller
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
                'data' => Periodos::all(),
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
            if ( $json['estado'] == 1 ) {
                $periodos = Periodos::all();
                foreach ($periodos as $val)
                {
                    $val->estado = false;
                    $val->save();
                }
            }
            $data = array(
                'status' => 201,
                'data' => Periodos::create($json),
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
     * @param  \App\Periodos  $periodo
     * @return \Illuminate\Http\Response
     */
    public function show(Periodos $periodo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Periodos  $periodo
     * @return \Illuminate\Http\Response
     */
    public function edit(Periodos $periodo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Periodos  $periodo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Periodos $periodo)
    {
        $auth = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $periodo->nombre = mb_strtoupper($json->nombre);
            $periodo->estado = $json->estado;
            $periodo->updated_at = Carbon::now();
            if ( $json->estado == 1 ) {
                $periodos = Periodos::all();
                foreach ($periodos as $val)
                {
                    $val->estado = false;
                    $val->save();
                }
            }
            $data = array(
                'status' => 201,
                'data' => $periodo->save(),
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
     * @param  \App\Periodos  $periodo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Periodos $periodo)
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
                'data' => Periodos::all()->where('estado', 1)->first(),
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
                'data' => Periodos::all()->where('estado', 1)->first()
            )
        );
    }
}
