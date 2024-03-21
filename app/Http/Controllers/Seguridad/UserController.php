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
use Carbon\Carbon;
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
                $xempresa       = Empresa::select('empDes', 'empImg')->where('empId', $user->empId)->get();
                $empresa        =  $xempresa[0]['empDes'];
                $imgEmp         =  $xempresa[0]['empImg'];
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
                        'menu'     => $menu,
                        'imgEmp'   => $imgEmp
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
        $token = $request->header('access-token');
        $datos  = viewTblUser::select('*')
                ->where('empId', $request['empId'])
                ->where('id',$request['idUser'])
                ->get();
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
        $empId  = $request['empId'];
        $val    = User::select('name')
                        ->where('name', $name)
                        ->where('empId', $empId)
                        ->get();
        $count  = 0;
            foreach ($val as $item) {
                    $count = $count + 1;
                }
        return $count;
    }

    public function ins_Users(Request $request)
    {
        $data        = request()->all();
        $empId       = $request['empId'];
        $nameI       = $request['name'];
        $emp         = $request['emp'];       
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

                $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $nameI , $emp , $request->log['0']['accDes']);
                 dispatch($job);                
                $resources = array(
                    array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
                );
                return response()->json($resources, 200);
    }

    public function up(Request $request)
    {
        $rest             = request()->all();
        $data             = json_decode(base64_decode($rest['user']));      
        $xid              = $data->usrid;
        $emploNom         = $data->emploNom;
        $emploApe         = $data->emploApe;
        $avatar           = $data->avatar;
        $fecha            = $data->emploFecNac;
        $emploFecNac      = $fecha->year . '-' . $fecha->month . '-' . $fecha->day;
        $mantenerPassword = $data->mantenerPassword;
        $rol              = $data->rol;
        $gerencia         = $data->gerencia;
        $emploPassword    = $data->emploPassword;
        $empId            = $rest['empId'];
        $name             = $rest['name'];
        $user             = User::find($xid);
        $valida           = 0;
       
        if($mantenerPassword == 1){
            if($rol > 0 ){
                $valida = $user->update([
                    'rolId'    => $rol,
                ]);
            }
                $valida = $user->update([
                    'reinicio' => 'N'
                ]);
        }else{
            if($rol > 0 ){
                $valida = $user->update([
                    'rolId'    => $rol,
                    'reinicio' => 'N'
                ]);
            }
            $valida = $user->update([               
                'password' => bcrypt($emploPassword),
                'reinicio' => 'N'
            ]);
        }
        
        if(is_null($gerencia) || $gerencia == ''){
            $gerencia = 0;
        }
 
        
            $empleado = Empleado::where('id', $xid)->get();
            if (isset($empleado)) {
                $valida =   Empleado::where('id', $xid)->update([
                    'emploNom'    => $emploNom,
                    'emploApe'    => $emploApe,
                    'emploFecNac' => $emploFecNac,
                    'gerId'       => $gerencia,
                    'emploAvatar' => $avatar
                ]);
            
            }
         

         if ($valida == 1) {                        
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes']);
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
    }

    function getUsuarios(Request $request)
    {
        $xid    = $request->userid;
        $datos   = User::select('emploAvatar')
        ->join('parm_empleados', 'users.id', '=', 'parm_empleados.id')
        ->where('users.id', $xid)->get();
        return response()->json($datos, 200);

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

    function getUsuario(Request $request)
    {
        return $request;

    }

    function reiniciar(Request $request){
        $rest        = request()->all();
        $data        = json_decode(base64_decode($rest['user'])); 
        $xname       = $data->name; 
        $xid         = $data->usrid;
        try{
            $empId       = $rest['empId'];

        }catch(Exception $error){
            $empId       = $data->empId; 

        }
      
        $name        = $rest['name'];
        $user        = User::find($xid);
       
        $valida      = $user->update([               
            'password' => bcrypt($xname),
            'reinicio' => 'S'
        ]);

        if ($valida == 1) {                        
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes'].'('.$xname.')');
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
    }


    function deshabilitar(Request $request){ 
        $rest        = request()->all();
        $data        = json_decode(base64_decode($rest['user'])); 
        $xname       = $data->name; 
        $xid         = $data->usrid;
        $name        = $rest['name'];
        $user        = User::find($xid);
       
        $valida      = $user->update([               
            'password' => bcrypt($xname),
            'reinicio' => 'S',
            'activado' => 'D'
        ]);

        try{
            $empId       = $rest['empId'];

        }catch(Exception $error){
            $empId       = $data->empId; 
        }


        if ($valida == 1) {                        
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes'].'('.$xname.')');
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
    }

    function habilitar(Request $request){ 
        $rest        = request()->all();
        $data        = json_decode(base64_decode($rest['user'])); 
        $xname       = $data->name; 
        $xid         = $data->usrid;
        $name        = $rest['name'];
        $user        = User::find($xid);

        try{
            $empId       = $rest['empId'];

        }catch(Exception $error){
            $empId       = $data->empId; 
        }

        $valida      = $user->update([               
            'password' => bcrypt($xname),
            'reinicio' => 'S',
            'activado' => 'A'
        ]);

        if ($valida == 1) {                        
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes'].'('.$xname.')');
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
    }

}
