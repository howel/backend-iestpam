<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('api')->group( function() {

    /** Login */
    Route::post('login', 'Auth\AuthController@login');
    Route::post('signUp', 'Auth\AuthController@signUp');
    Route::get('signUp/{dni}', 'Auth\AuthController@validateDni');
    Route::post('signUp/{email}', 'Auth\AuthController@validateEmail');
    Route::post('me', 'Auth\AuthController@me');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('password', 'Auth\AuthController@password');
    Route::get('admin/dashboard', 'HomeController@dashboardAdmin');

    /** Usuarios */
    Route::resource('usuarios', 'UserController');

    /** Seguimientos */
    Route::resource('seguimiento', 'SeguimientoController');

    /** Carreras */
    Route::resource('carreras', 'CarrerasController');
    Route::get('carreras/active/all', 'CarrerasController@active');
    Route::get('carreras/active/ex/token', 'CarrerasController@activeExToken');

    /** Alumnos */
    Route::resource('alumnos', 'AlumnosController');

    /** Periodos */
    Route::resource('periodos', 'PeriodosController');
    Route::get('periodos/by/active', 'PeriodosController@active');
    Route::get('periodos/active/ex/token', 'PeriodosController@activeExToken');

    /** Etapas */
    Route::resource('etapas', 'EtapasController');
    Route::get('etapas/active/all', 'EtapasController@active');
    Route::get('etapas/active/ex/token', 'EtapasController@activeExToken');
    Route::get('etapa/actual', 'EtapasController@etapaActual');

    /** Ciclos */
    Route::resource('ciclos', 'CiclosController');
    Route::get('ciclos/{etapa}/all', 'CiclosController@indexByEtapa');
    Route::get('ciclos/by/etapa/all', 'CiclosController@byEtapa');
    Route::get('ciclos/filter/active/all', 'CiclosController@active');
    Route::get('ciclos/filter/active/ex/token', 'CiclosController@activeExToken');

    /** TipoMatriculas */
    Route::resource('tipoMatriculas', 'TipoMatriculasController');
    Route::get('tipoMatriculas/active/all', 'TipoMatriculasController@active');
    Route::get('tipoMatriculas/active/ex/token', 'TipoMatriculasController@activeExToken');

    /** TipoPagos */
    Route::resource('tipoPagos', 'TipoPagosController');
    Route::get('tipoPagos/{tipoMatricula}/all', 'TipoPagosController@indexByTipoMatricula');
    Route::get('tipoPagos/active/all', 'TipoPagosController@active');
    Route::get('tipoPagos/active/ex/token', 'TipoPagosController@activeExToken');

    /** Matriculas */
    Route::resource('matriculas', 'MatriculasController');
    Route::get('matriculas/{alumno}/by', 'MatriculasController@byAlumno');
    Route::get('matriculas/by/periodo/etapa/all', 'MatriculasController@byPeriodoAndEtapa');
    Route::get('matriculas/{carrera}/{ciclo}/{on}/{off}/report', 'MatriculasController@report');

    /** Pagos */
    Route::resource('pagos', 'PagosController');
    Route::get('pagos/{matricula}/all', 'PagosController@indexByMatricula');

    /**
     * Config
     */
    // Migrate:
    Route::get('/migrate', function() {
        Artisan::call('migrate');
        return '<h1>Successfully migrated.</h1>';
    });

    //Seeders:
    Route::get('/seed', function() {
        Artisan::call('db:seed');
        return '<h1>Records inserted successfully.</h1>';
    });

    //Clear Config cache:
    Route::get('/config-cache', function() {
        Artisan::call('config:cache');
        return '<h1>Successfully cleaned configuration.</h1>';
    });

});
