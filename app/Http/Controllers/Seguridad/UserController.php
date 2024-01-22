<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Models\Parametros\Empleado;
use App\Models\Seguridad\Empresa;
use App\Models\Seguridad\Roles;
use App\Models\User;
use App\Models\viewTblUser;
use App\Models\viewUser;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    public function authenticateUser(Request $request)
    {

        try{

            $data     = $request->all();
            $rep      = json_decode(base64_decode($data['Authentication']));
            $email    = $rep->email;
            $password = $rep->password;
            $remember = '';
            $crf      = '';

        if (Auth::attempt(['name' => $email, 'password' => $password], $remember)) {
            $token = Str::random(60);
            $user  = Auth::user();
            
            if ($user->activado == 'A') {
                $idUser = $user->id;
                User::where('id', $idUser)
                    ->update(['token' => $token]);             
                
                $crf = csrf_token();
                $imgx = Empleado::select('emploAvatar')->where('id', $idUser)->get();

                if(sizeof($imgx) > 0){
                  $img  = $imgx[0]['emploAvatar'];
                }else{
                    $img = '';
                }

                $xrol           = Roles::select('rolDes')->where('rolId', $user->rolId)->get();
                $rol            = $xrol[0]['rolDes'];                
                $xempresa       = Empresa::select('empDes')->where('empId', $user->empId)->get();
                $empresa        =  $xempresa[0]['empDes'];
                $controller     =  new MenuController;
                $menu           = $controller->index($user->empId , $user->rolId);   
                
                $resources =
                    array(
                        'id'       => $user->id,
                        'name'     => $user->name,
                        'token'    => $token,
                        'reinicio' => $user->reinicio,
                        'crf'      => $crf,
                        'img'      => $img,
                        'rol'      => $rol,
                        'empresa'  => $empresa,
                        'menu'     => $menu
                    );
                
                  $etaId    = 1;
                  $etaDesId = 1;
                  $name     = $user->name;
                  $empId    = $user->empId; 

                  $job = new LogSistema( $etaId , $etaDesId , $name , $empId , 'LOGEO DE USUARIO');
                  dispatch($job);

                return response()->json($resources, 200);
            } else {
                $resources = array(
                    array(
                        "error" => "1", 'mensaje' => "Usuario desactivado",
                        'type' => 'danger'
                    )
                );
                return response()->json($resources, 200);
            }
        } else {
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "El usuario no logeado",
                    'type' => 'danger'
                )
            );
            return response()->json($resources, 200);
        }

        }catch(QueryException $ex){
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "Error en el servidor",
                    'type' => 'danger',
                    "des" => $ex
                )
            );
            return response()->json($resources, 200);
        }
    }

    public function trabUsuarios(Request $request)
    {

        return $data = viewTblUser::select('*')->where('empId', $request['empId'])->get();
    }

    public function trabUsuariosAmd(Request $request)
    {
        return $data = viewTblUser::select('*')->get();
    }

    public function getUser(Request $request)
    {
        $header = $request->header('access-token');
        $datos = viewUser::select('name', 'imgName', 'rolDes')->where('token', $header)->get();
        return response()->json($datos, 200);
    }

    public function setUserSession(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::all()->where('token', $header);
        $data   =  $request->all();

        if (isset($val)) {
            foreach ($val as $item) {
                if ($item->activado = 'A') {
                    $id   = $item->id;
                    $name = $item->name;
                    $token = $item->token;
                }
            }

            if ($id > 0) {
                foreach ($data as $itemx) {
                    $usuariox =    $itemx['usuario'];
                }

                if ($usuariox == $name && $header == $token) {
                    $resources = array(
                        array(
                            "error" => "99", 'mensaje' => "Usuario valido",
                            'type' => 'success'
                        )
                    );
                    return response()->json($resources, 200);
                } else {
                    $resources = array(
                        array(
                            "error" => "4", 'mensaje' => "Usuario invalido",
                            'type' => 'danger'
                        )
                    );
                    return response()->json($resources, 200);
                }
            } else {
                $resources = array(
                    array(
                        "error" => "3", 'mensaje' => "Sin datos encontrados",
                        'type' => 'danger'
                    )
                );
            }
        } else {
            $resources = array(
                array(
                    "error" => "2", 'mensaje' => "Usuario invalido",
                    'type' => 'danger'
                )
            );
        }
    }

    public function valUsuario(Request $request)
    {  
        $data   = request()->all();
        $name   = $data['emploName'];
        $val    = User::select('name')
                        ->where('name', $name)
                        ->get();
        $count  = 0;
            foreach ($val as $item) {
                    $count = $count + 1;
                }
        return $count;
    }

    public function ins_Users(Request $request)
    {
        $empId       = $request['empId'];
        $nameI       = $request['name'];
        $emp         = $request['emp'];
        $data        = request()->all();
        $name        = $data['empName'];
        $imgName     = $data['emploAvatar'];
        $password    = $name;
        $emploNom    = strtoupper($data['emploNom']);
        $emploApe    = strtoupper($data['emploApe']);
        $fecha       = $data['emploFecNac'];
        $emploFecNac = $fecha['year'] . '-' . $fecha['month'] . '-' . $fecha['day'];
        $rolId       = $data['rol'];
        $gerId       = $data['gerId'];

            $affect =User::create([
                    'email'    => '',
                    'password' => bcrypt($password),
                    'name'     => $name,
                    'imgName'  => '',
                    'activado' => 'A',
                    'token'    => '',
                    'rolId'    => $rolId,
                    'reinicio' => 'S',
                    'empId'    => $empId

                ]);
              

                Empleado::create([
                    'id'          => $affect->id,
                    'emploNom'    => $emploNom,
                    'emploApe'    => $emploApe,
                    'emploFecNac' => $emploFecNac,
                    'emploAvatar' => $imgName,
                    'empId'       => $empId,
                    'gerId'       => $gerId

                ]);

                $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $nameI , $emp , $request->log['0']['accetaDes']);
                 dispatch($job);                
                $resources = array(
                    array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
                );
                return response()->json($resources, 200);
    }

    public function up_Password(Request $request)
    {
        
        $header = $request->header('access-token');
        $val    = User::select('token', 'id', 'activado', 'reinicio')->where('token', $header)->get();
        $data   = request()->all();

        if ($header == '') {
            return response()->json('error', 203);
        } else {


            foreach ($val as $item) {
                if ($item->activado == 'A') {
                    $id = $item->id;
                    $reinicio = $item->reinicio;
                }
            }


            if ($id > 0) {
                if ($reinicio == 'S') {
                    User::where('id', $id)
                        ->update([
                            'password' => bcrypt($data['password']),
                            'reinicio' => 'N'

                        ]);
                    $resources = array(
                        array(
                            "error" => "0", 'mensaje' => "Password actualizada",
                            'type' => 'success'
                        )
                    );
                    return response()->json($resources, 200);
                } else {
                    $resources = array(
                        array(
                            "error" => "1", 'mensaje' => "El usuario no está marcado para reinicio" . $reinicio,
                            'type' => 'danger'
                        )
                    );
                    return response()->json($resources, 200);
                }
            } else {
                return response()->json('error', 203);
            }
        }
    }

    public function up(Request $request)
    {
        $header = $request->header('access-token');
        $data   = request()->all();
        $xid    = $data['id'];
        $nameI  = $request['name'];
        $emp    = $request['emp'];

                try {

                    $idRol =  intval($data['rol']);
                    $idGer =  intval($data['gerencia']);

                    $user = User::find($xid);

                    if ($data['reinicio'] == 'S') {
                        $valida = $user->update([
                            'rolId'    => $idRol,
                            'reinicio' => $data['reinicio'],
                            'activado' => $data['activado'],
                            'password' => bcrypt($user->name) 
                        ]);
                    } else {
                        $valida = $user->update([
                            'rolId'    => $idRol,
                            'reinicio' => $data['reinicio'],
                            'activado' => $data['activado']
                        ]);
                    }

                    if( !isNull($data['emploNom'])){
                       Empleado::where('id', $xid)->update([
                            'emploNom'    => $data['emploNom'],
                            'emploApe'    => $data['emploApe'],
                            'emploFecNac' => $data['emploFecNac'],
                            'gerId'       => $idGer
                        ]);
                    }

                  

                    if ($valida == 1) {
                        
                        $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $nameI , $emp , $request->log['0']['accetaDes']);
                            dispatch($job);                
                        $resources = array(
                            array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
                        );
                        return response()->json($resources, 200);
                       
                    } else {
                        $resources = array(
                            array(
                                "error" => "1", 'mensaje' => "Error en el servidor",
                                'type' => 'danger'
                            )
                        );
                        return response()->json($resources, 200);
                    }
                } catch (Exception $ex) {
                    return $ex;
                }
    }

    function getUsuarios(Request $request)
    {

        $xid    = $request->idUser;
        return User::select('idRol', 'gerId')
            ->join('empleados', 'users.id', '=', 'empleados.id')
            ->where('users.id', $xid)->get();
    }

    public function upUsuario2(Request $request)
    {
        try {
            $data = request()->all();
            foreach ($data as $itemx) {
                $imgName =  $itemx['imgName'];
                $id      =  $itemx['idUser'];
            }

            $valida = Empleado::where('id', $id)->update([
                'emploAvatar' => $imgName
            ]);
            return $valida;
            if ($valida == 1) {
                $resources = array(
                    array(
                        "error" => "0", 'mensaje' => "Usuario actualizado",
                        'type' => 'success'
                    )
                );
                return response()->json($resources, 200);
            } else {
                $resources = array(
                    array(
                        "error" => "1", 'mensaje' => "Error en el servidor",
                        'type' => 'danger'
                    )
                );
                return response()->json($resources, 200);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
