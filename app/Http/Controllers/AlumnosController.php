<?php

namespace App\Http\Controllers;

use App\Alumnos;
use Illuminate\Http\Request;

class AlumnosController extends Controller
{
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
                'data' => Alumnos::all(),
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
        $validator = Validator::make($json, [
            'id' => 'nullable',
            'carreras_id' => 'required',
            'dni' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'email' => 'required',
            'sexo' => 'required',
            'edad' => 'required',
            'nacimiento' => 'required',
            'telefono' => 'nullable',
            'direccion' => 'nullable',
            'estado' => 'required',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ]);
        if ( $validator->fails() )
        {
            $data = response()->json([
                'status' => 'error',
                'data' => 'Datos del Formulario Incompletos.',
                'token' => $auth/*$validator->messages()*/
            ]);
        } else {
            try {
                $json['nombres'] = mb_strtoupper($json['nombres']);
                $json['apellidos'] = mb_strtoupper($json['apellidos']);
                $json['nacimiento'] = Carbon::parse($json['nacimiento']);
                $json['direccion'] = $json['direccion'] ? mb_strtoupper($json['direccion']) : null;
                $json['created_at'] = Carbon::now();
                $json['updated_at'] = Carbon::now();
                $data = array(
                    'status' => 201,
                    'data' => Alumnos::create($json),
                    'token' => $auth
                );
            } catch (\Exception $ex) {
                $data = array(
                    'status' => 403,
                    'data' => $ex->getMessage(),
                    'token' => $auth
                );

            }
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Alumnos  $alumnos
     * @return \Illuminate\Http\Response
     */
    public function show(Alumnos $alumnos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Alumnos  $alumnos
     * @return \Illuminate\Http\Response
     */
    public function edit(Alumnos $alumnos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alumnos  $alumnos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumnos $alumnos)
    {
        $auth = auth()->refresh();
        $json = json_decode($request->json, JSON_FORCE_OBJECT);
        $validator = Validator::make($json, [
            'id' => 'required',
            'carreras_id' => 'required',
            'dni' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'email' => 'required',
            'sexo' => 'required',
            'edad' => 'required',
            'nacimiento' => 'required',
            'telefono' => 'nullable',
            'direccion' => 'nullable',
            'estado' => 'required',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ]);
        if ( $validator->fails() )
        {
            $data = response()->json([
                'status' => 'error',
                'data' => 'Datos del Formulario Incompletos.',
                'token' => $auth/*$validator->messages()*/
            ]);
        } else {
            try {
                $json['nombres'] = mb_strtoupper($json['nombres']);
                $json['apellidos'] = mb_strtoupper($json['apellidos']);
                $json['nacimiento'] = Carbon::parse($json['nacimiento']);
                $json['direccion'] = $json['direccion'] ? mb_strtoupper($json['direccion']) : null;
                $json['created_at'] = Carbon::now();
                $json['updated_at'] = Carbon::now();
                $data = array(
                    'status' => 201,
                    'data' => Alumnos::create($json),
                    'token' => $auth
                );
            } catch (\Exception $ex) {
                $data = array(
                    'status' => 403,
                    'data' => $ex->getMessage(),
                    'token' => $auth
                );

            }
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alumnos  $alumnos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alumnos $alumnos)
    {
        //
    }
}
