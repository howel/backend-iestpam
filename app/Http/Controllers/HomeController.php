<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardAdmin()
    {
        $periodo = DB::table('periodos')->where('estado', 1)->first();
        return response()->json(
            array(
                'status' => 200,
                'data' => array(
                    'matriculas' => DB::table('matriculas')->where('periodos_id', $periodo->id)->count(),
                    'usuarios' => DB::table('usuarios')->where('perfil', 'ALUMNO(A)')->whereYear('created_at', $periodo->nombre)->count()
                ),
                'token' => auth()->refresh(),
            )
        );
    }
}
