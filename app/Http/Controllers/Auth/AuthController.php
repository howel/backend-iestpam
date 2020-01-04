<?php

namespace App\Http\Controllers\Auth;

use App\Alumnos;
use App\Carreras;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signUp', 'validateDni', 'validateEmail', 'srcImgExToken']]);
    }

    /**
     * Get the login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $json = json_decode($request->json, JSON_FORCE_OBJECT);
        $validator = Validator::make($json, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            $data = array(
                'status' => 202,
                'data' => 'Credenciales Invalidas.'
            );
        } else {
            try {
                // Intente verificar las credenciales y crear un token para el usuario
                if (! $token = auth()->attempt($json)) {
                    $data = array(
                        'status' => 401,
                        'data' => 'No podemos encontrar una cuenta con estas credenciales.'
                    );
                } else {
                    // Todo bien así que devuelve el token
                    $auth = auth()->user();
                    if ( $auth->estado == 1 || $auth->estado == 3 ) {
                        $data = $this->respondWithToken($token);
                        if ( $auth->perfil == 'ALUMNO(A)' ) {
                            $alumno = Alumnos::all()->where('dni', $auth->dni)->where('estado', 1)->first();
                            if ( !$alumno ) {
                                $data = array(
                                    'status' => 401,
                                    'data' => 'Usted se Encuentra Inabilitado, comuniquece con el Administrador del Sistema.'
                                );
                            } else {
                                $data = $this->respondWithToken($token);
                                $alumno->carreras = Carreras::find($alumno->carreras_id);
                                $data['alumno'] = $alumno;
                            }
                        }
                    } else {
                        $data = array(
                            'status' => 401,
                            'data'=> 'Su Cuenta se Encuentra Bloqueado, comuniquece con el Administrador del Sistema'
                        );
                    }
                }
            } catch (JWTException $e) {
                // Algo salió mal con JWT Auth.
                $data = array(
                    'status' => 500,
                    'data' => 'Error al iniciar sesión, por favor intente de nuevo.'
                );
            }
        }
        return response()->json($data);
    }

    /**
     * Get the signUp.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
        try {
            $json = json_decode($request->json, JSON_FORCE_OBJECT);
            $json['nombres'] = mb_strtoupper($json['nombres']);
            $json['apellidos'] = mb_strtoupper($json['apellidos']);
            $json['nacimiento'] = Carbon::parse($json['nacimiento']);
            $json['created_at'] = Carbon::now();
            $json['updated_at'] = Carbon::now();
            Alumnos::create($json);
            $json['password'] = Hash::make($json['password']);
            if ($request->hasFile('src_img')) {
                $name = $request->file('src_img')->store('/');
                Storage::move($name, 'public/avatars/'.$name);
                $json['src_img'] = $name;
            }
            $data = array(
                'status' => 201,
                'data'=> User::create($json)
            );
            } catch (\Exception $ex) {
                $data = array(
                    'status' => 401,
                    'data' => $ex->getMessage()
                );
            }
        return response()->json($data);
    }

    /**
     * Get the validateDni.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateDni($dni)
    {
        $data = array(
            'status' => 200,
            'data' => true
        );
        $user = User::all()->where('dni', $dni)->first();
        if ( !$user ) {
            $data['data'] = true;
        } else {
            $data['data'] = false;
        }
        return response()->json($data);
    }

    /**
     * Get the validateDni.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateEmail($email)
    {
        $data = array(
            'status' => 200,
            'data' => true
        );
        $user = User::all()->where('email', $email)->first();
        if ( !$user ) {
            $data['data'] = true;
        } else {
            $data['data'] = false;
        }
        return response()->json($data);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the User out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['status' => 200]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth()->refresh();
        return response()->json([
            'status' => 200,
            'token' => $token,
            'expire' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function password(Request $request)
    {
        $token = auth()->refresh();
        $json = json_decode($request->json);
        try {
            if (!Hash::check($json->password, auth()->user()->password)) {
                $data = array(
                    'status' => 403,
                    'data' => 'La Contraseña Actual es Incorrecta',
                    'token' => $token
                );
            } else {
                $user = User::find(auth()->user()->id);
                $user->password = Hash::make($json->pass);
                $data = array(
                    'status' => 201,
                    'data' => $user->save(),
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
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'token' => $token,
            'user' => auth()->user(),
            'expire' => auth()->factory()->getTTL() * 60
        ];
    }
}
