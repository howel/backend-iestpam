<?php

namespace App\Http\Controllers;

use App\Ciclos;
use App\Etapas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CiclosController extends Controller
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
                'data' => Ciclos::all(),
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
            $json['created_at'] = Carbon::now();
            $json['updated_at'] = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => Ciclos::create($json),
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
     * @param  \App\Ciclos  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function show(Ciclos $ciclo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ciclos  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function edit(Ciclos $ciclo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ciclos  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ciclos $ciclo)
    {
        $auth = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $ciclo->etapas_id = $json->etapas_id;
            $ciclo->nombre = $json->nombre;
            $ciclo->estado = $json->estado;
            $ciclo->updated_at = Carbon::now();
            $data = array(
                'status' => 201,
                'data' => $ciclo->save(),
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
     * @param  \App\Ciclos  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ciclos $ciclo)
    {
        //
    }

    /**
     * @param Etapas $etapa
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexByEtapa(Etapas $etapa)
    {
        $ciclos = Ciclos::where('etapas_id', $etapa->id)->get();
        foreach ($ciclos as $val)
        {
            $val->etapas = Etapas::find($val->etapas_id);
        }
        return response()->json(
            array(
                'status' => 200,
                'data' => $ciclos,
                'token' => auth()->refresh()
            )
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byEtapa()
    {
        $token = auth()->refresh();
        $etapa = [];
        foreach (Etapas::all() as $val)
        {
            if ($this->checkInRange($val->inicio, $val->final)) {
                $etapa = $val;
            }
        }
        if ( !$etapa ) {
            $data = array(
                'status' => 401,
                'data' => 'Etapas del Año no Actualizadas',
                'token' => $token
            );
        } else {
            $ciclos = DB::table('ciclos')->where('etapas_id', $etapa->id)->get();
            $data = array(
                'status' => 200,
                'data' => $ciclos,
                'token' => $token
            );
        }
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        return response()->json(
            array(
                'status' => 200,
                'data' => DB::table('ciclos')->where('estado', 1)->get(),
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
                'data' => DB::table('ciclos')->where('estado', 1)->get()
            )
        );
    }

    /**
     * @param $start
     * @param $finished
     * @return bool
     */
    private function checkInRange($start, $finished)
    {
        $fecha1 = strtotime($start);
        $fecha2 = strtotime($finished);
        $fechaComprobar = strtotime(Carbon::now());
        if (($fechaComprobar >= $fecha1) && ($fechaComprobar <= $fecha2)) {
            return true;
        } else{
            return false;
        }
    }

    /**
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return array
     */
    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for($date = $start_date; $date->lte($end_date); $date->addDay())
        {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }
}
