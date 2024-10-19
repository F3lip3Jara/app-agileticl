<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Models\Sd\SdOrden;
use App\Models\Sd\SdOrdeTemp;
use Illuminate\Http\Request;

class SdOrdController extends Controller
{
    public function index(Request $request){
          
        return SdOrden::select('*')       
        ->where('empId', $request->empId)       
        ->get();
    }

    public function ins(Request $request){
          
        $name    = $request['name'];
        $empId   = $request['empId'];

        
        $valida = SdOrdeTemp::all()
                        ->where('empId', $empId)
                        ->where('ordtCustShortText1', $request['0']['opedId'])
                        ->take(1);
        //si la variable es null o vacia elimino el rol
        if (sizeof($valida) > 0) {
            //en el caso que no se ecuentra vacia no puedo eliminar
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "La orden ya se encuentra en proceso",
                    'type' => 'danger'
                )
            );
            return response()->json($resources, 200);
        } else {

                $affected = SdOrdeTemp::create([
                    'empId'    => $empId,
                    'centroId' => 1,
                    'almId'    => 1,
                    'ordtCustShortText1'=>  $request['0']['opedId'],
                    'ordtTip' => 'S',
                    'ordtest'  => 'N', 
                ]);

                if (isset($affected)) {
                    $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes']);
                    dispatch($job);            
                    $resources = array(
                    array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
                    );
                    return response()->json($resources, 200);
                } else {
                    return response()->json('error', 204);
                }
            }
    }
}
