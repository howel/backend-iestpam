<?php

namespace App\Http\Controllers;

use App\Alumnos;
use App\Carreras;
use App\Ciclos;
use App\Etapas;
use App\Matriculas;
use App\Pagos;
use App\Periodos;
use App\TipoMatriculas;
use App\TipoPagos;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MatriculasController extends Controller
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
                'data' => Matriculas::all(),
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
            $duplicate = Matriculas::all()->where('alumnos_id', $json['alumnos_id'])
                ->where('carreras_id', $json['carreras_id'])->where('ciclos_id', $json['ciclos_id'])->first();
            if(!$duplicate) {
                $json['estado'] = 4;
                $json['created_at'] = Carbon::now();
                $json['updated_at'] = Carbon::now();
                $matricula = Matriculas::create($json);
                $partes = $json['tipo_pagos']['partes'];
                for ($i = 0; $i < $partes; $i++ ) {
                    Pagos::create(
                        [
                            'matriculas_id' => $matricula->id,
                            'tipo_pagos_id' => $json['tipo_pagos_id'],
                            'importe' => $matricula->total / $partes,
                            'estado' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]
                    );
                }
                /**
                 * Matriculas Estado
                 * 0 rechazado      danger
                 * 1 aprovado       success
                 * 2 proceso        info
                 * 3 observacion    warning
                 * 4 emitido        secundary
                 **/
                /**
                 * Pagos Estado
                 * 0 rechazado      danger
                 * 1 aprovado       success
                 * 2 proceso        info
                 * 3 observacion    warning
                 **/
                $data = array(
                    'status' => 201,
                    'data' => $matricula,
                    'token' => $token
                );
            } else {
                $data = array(
                    'status' => 401,
                    'data' => 'Ya Registro su Matrícula',
                    'token' => $token
                );
            }
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
     * @param  \App\Matriculas  $matricula
     * @return \Illuminate\Http\Response
     */
    public function show(Matriculas $matricula)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Matriculas  $matricula
     * @return \Illuminate\Http\Response
     */
    public function edit(Matriculas $matricula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Matriculas  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matriculas $matricula)
    {
        $token = auth()->refresh();
        $json = json_decode($request->json);
        try {
            $matricula->alumnos_id = $json->alumnos_id;
            $matricula->periodos_id = $json->periodos_id;
            $matricula->carreras_id = $json->carreras_id;
            $matricula->ciclos_id = $json->ciclos_id;
            $matricula->tipo_matriculas_id = $json->tipo_matriculas_id;
            $matricula->tasa_descuento = $json->tasa_descuento;
            $matricula->total = $json->total;
            $matricula->estado = $json->estado;
            $matricula->updated_at = Carbon::now();
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
     * @param  \App\Matriculas  $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matriculas $matricula)
    {
        //
    }

    /**
     * @param Alumnos $alumno
     * @return \Illuminate\Http\JsonResponse
     */
    public function byAlumno(Alumnos $alumno)
    {
        $matriculas = DB::table('matriculas')->where('alumnos_id', $alumno->id)->get();
        foreach ($matriculas as $val)
        {
            $val->periodos = Periodos::find($val->periodos_id);
            $val->carreras = Carreras::find($val->carreras_id);
            $val->ciclos = Ciclos::find($val->ciclos_id);
            $val->tipo_matriculas = TipoMatriculas::find($val->tipo_matriculas_id);
            $pagos = DB::table('pagos')->where('matriculas_id', $val->id)->get();
            foreach ($pagos as $item)
            {
                $item->tipo_pagos = TipoPagos::find($item->tipo_pagos_id);
            }
            $val->pagos = $pagos;
        }
        return response()->json(
            array(
                'status' => 200,
                'data' => $matriculas,
                'token' => auth()->refresh()
            )
        );
    }

    public function byPeriodoAndEtapa()
    {
        $token = auth()->refresh();
        $periodo = Periodos::all()->where('estado', 1)->first();
        if ( !$periodo ) {
            $data = array(
                'status' => 401,
                'data' => 'Periodo No Establecido',
                'token' => $token
            );
        } else {
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
                $matriculas = DB::table('matriculas')->select('matriculas.*')
                    ->join('ciclos', 'matriculas.ciclos_id', '=', 'ciclos.id')
                    ->where('periodos_id', $periodo->id)
                    ->where('ciclos.etapas_id', $etapa->id)->get();
                foreach ($matriculas as $val)
                {
                    $val->alumnos = Alumnos::find($val->alumnos_id);
                    $val->periodos = Periodos::find($val->periodos_id);
                    $val->carreras = Carreras::find($val->carreras_id);
                    $val->ciclos = Ciclos::find($val->ciclos_id);
                    $val->tipo_matriculas = TipoMatriculas::find($val->tipo_matriculas_id);
                    $pagos = DB::table('pagos')->where('matriculas_id', $val->id)->get();
                    foreach ($pagos as $item)
                    {
                        $item->tipo_pagos = TipoPagos::find($item->tipo_pagos_id);
                    }
                    $val->pagos = $pagos;
                }
                $data = array(
                    'status' => 200,
                    'data' => $matriculas,
                    'token' => $token
                );
            }
        }
        return response()->json($data);
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
     * @param $carrera
     * @param $ciclo
     * @param $on
     * @param $off
     * @return \Illuminate\Http\JsonResponse
     */
    public function report($carrera, $ciclo, $on, $off)
    {
        //$token = auth()->refresh();
        $periodo = Periodos::all()->where('estado', 1)->first();
        if ( !$periodo ) {
            $data = array(
                'status' => 401,
                'data' => 'Periodo No Establecido',
                'token' => $token
            );
        } else {
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
                $matriculas = DB::table('matriculas')->select('matriculas.*')
                    ->join('ciclos', 'matriculas.ciclos_id', '=', 'ciclos.id')
                    ->where('periodos_id', $periodo->id)
                    ->where('ciclos.etapas_id', $etapa->id)
                    ->whereDate('matriculas.created_at', '>=', Carbon::parse($on)->format('Y-m-d'))
                    ->whereDate('matriculas.created_at', '<=', Carbon::parse($off)->format('Y-m-d'))->get();
                if ( $carrera != 'null' && $ciclo != 'null' ) {
                    $matriculas = DB::table('matriculas')->select('matriculas.*')
                        ->join('ciclos', 'matriculas.ciclos_id', '=', 'ciclos.id')
                        ->where('periodos_id', $periodo->id)
                        ->where('ciclos.etapas_id', $etapa->id)
                        ->where('matriculas.carreras_id', $carrera)
                        ->where('matriculas.ciclos_id', $ciclo)
                        ->whereDate('matriculas.created_at', '>=', Carbon::parse($on)->format('Y-m-d'))
                        ->whereDate('matriculas.created_at', '<=', Carbon::parse($off)->format('Y-m-d'))->get();
                } else if ( $carrera != 'null' ) {
                    $matriculas = DB::table('matriculas')->select('matriculas.*')
                        ->join('ciclos', 'matriculas.ciclos_id', '=', 'ciclos.id')
                        ->where('periodos_id', $periodo->id)
                        ->where('ciclos.etapas_id', $etapa->id)
                        ->where('matriculas.carreras_id', $carrera)
                        ->whereDate('matriculas.created_at', '>=', Carbon::parse($on)->format('Y-m-d'))
                        ->whereDate('matriculas.created_at', '<=', Carbon::parse($off)->format('Y-m-d'))->get();
                } else if ( $ciclo != 'null' ) {
                    $matriculas = DB::table('matriculas')->select('matriculas.*')
                        ->join('ciclos', 'matriculas.ciclos_id', '=', 'ciclos.id')
                        ->where('periodos_id', $periodo->id)
                        ->where('ciclos.etapas_id', $etapa->id)
                        ->where('matriculas.ciclos_id', $ciclo)
                        ->whereDate('matriculas.created_at', '>=', Carbon::parse($on)->format('Y-m-d'))
                        ->whereDate('matriculas.created_at', '<=', Carbon::parse($off)->format('Y-m-d'))->get();
                }
                foreach ($matriculas as $val)
                {
                    $val->alumnos = Alumnos::find($val->alumnos_id);
                    $val->periodos = Periodos::find($val->periodos_id);
                    $val->carreras = Carreras::find($val->carreras_id);
                    $val->ciclos = Ciclos::find($val->ciclos_id);
                    $val->tipo_matriculas = TipoMatriculas::find($val->tipo_matriculas_id);
                    $pagos = DB::table('pagos')->where('matriculas_id', $val->id)->get();
                    foreach ($pagos as $item)
                    {
                        $item->tipo_pagos = TipoPagos::find($item->tipo_pagos_id);
                    }
                    $val->pagos = $pagos;
                }
                $data = array(
                    'title' => 'Matriculas',
                    'titulo' => 'Matriculas',
                    'matriculas' => $matriculas
                );
                $pdf = PDF::loadView('pdf.matriculas.report', $data);
                return $pdf->stream();
            }
        }
        return response()->json($data);
    }
}
