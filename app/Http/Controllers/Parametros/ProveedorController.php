<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Models\Parametros\Proveedor;
use App\Models\viewProveedores;
use Exception;
use Freshwork\ChileanBundle\Facades\Rut;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {

        return viewProveedores::all();
    }

    public function update(Request $request)
    {   
        $name        = $request['name'];
        $empId       = $request['empId'];

        $affected = Proveedor::where('prvId', $request['prvId'])->update([
            'prvNom'   => $request->prvNom,
            'prvNom2'  => $request->prvNom2,
            'prvGiro'  => $request->prvGiro,
            'prvDir'   => $request->prvDir,
            'prvNum'   => $request->prvNum,
            'prvTel'   => $request->prvTel,
            'prvMail'  => $request->prvMail,
            'prvCli'   => $request->prvCli,
            'prvPrv'   => $request->prvPrv,
            'paiId'    => $request->paiId,
            'regId'    => $request->regId,
            'comId'    => $request->comId,
            'ciuId'    => $request->ciuId,
            'prvAct'   => $request->prvAct
        ]);

        if ($affected > 0) {
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accetaDes']);
            dispatch($job);            
            $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }

    public function ins(Request $request)
    {
        $name        = $request['name'];
        $empId       = $request['empId'];

        $affected = Proveedor::create([
            'empId'    => $empId,
            'prvRut'   => $request->prvRut,
            'prvNom'   => $request->prvNom,
            'prvNom2'  => $request->prvNom2,
            'prvGiro'  => $request->prvGiro,
            'prvDir'   => $request->prvDir,
            'prvNum'   => $request->prvNum,
            'prvTel'   => $request->prvTel,
            'prvMail'  => $request->prvMail,
            'prvCli'   => $request->prvCli,
            'prvPrv'   => $request->prvPrv,
            'paiId'    => $request->paiId,
            'regId'    => $request->regId,
            'comId'    => $request->comId,
            'ciuId'    => $request->ciuId,
            'prvAct'   => 'S'
        ]);

        if (isset($affected)) {
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accetaDes']);
            dispatch($job);            
            $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }


    public function valPrvRut(Request $request)
    {
        $data     = request()->all();
        $prvRut   = $data['prvRut'];
        $val      = Proveedor::select('prvRut')->where('prvRut', $prvRut)->get();
        $count  = 0;
        foreach ($val as $item) {
            $count = $count + 1;
        }

        if ($count >= 1) {
            $resources = array(
                array("error" => '0', 'mensaje' => "rut ya existe", 'type' => 'warning')
            );
            return response()->json($resources, 200);
        } else {
            try {

                $rut = Rut::parse($prvRut)->validate();

                if ($rut != 1) {
                    $resources = array(
                        array("error" => '0', 'mensaje' => "rut incorrecto", 'type' => 'warning')
                    );
                    return response()->json($resources, 200);
                } else {
                    $resources = array(
                        array("error" => '1', 'mensaje' => "rut correcto", 'type' => 'warning')
                    );
                    return response()->json($resources, 200);
                }
            } catch (Exception $ex) {
                $resources = array(
                    array("error" => '0', 'mensaje' => "rut incorrecto", 'type' => 'warning')
                );
                return response()->json($resources, 200);
            }
        }
    }


    public function datPrv(Request $request)
    {
        $data   = $request->all();
        $datos = Proveedor::select(['paiId', 'regId', 'comId', 'ciuId', 'prvAct'])->where('prvId', $data['prvId'])->get();

        foreach ($datos as $item) {
            $resources = 
                array(
                    'paiId'     => $item->paiId,
                    'regId'     => $item->regId,
                    'comId'     => $item->comId,
                    'ciuId'     => $item->ciuId,
                    'prvAct'    => $item->prvAct,
                )
            ;
        }
        return $resources;
    }

    public function selCliente(Request $request)
    {
        $data = $request->all();
        $es_cliente = $data['es_cliente'];
        return viewProveedores::all()->where('es_cliente', $es_cliente);
    }
}
