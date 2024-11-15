<?php

namespace App\Http\Controllers\Sd;
use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Jobs\SdOrdenJobTemp;
use App\Models\Sd\SdOrden;
use App\Models\Sd\SdOrdenDet;
use App\Models\Sd\SdOrdeTemp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SdOrdController extends Controller
{
    public function index(Request $request){

        $query = SdOrden::select('*')
        ->join('sd_centro', 'sd_centro.centroId', '=', 'sd_orden.centroId')   
        ->join('sd_centro_alm', 'sd_centro_alm.almId', '=', 'sd_orden.almId')   
        ->where('sd_orden.empId', $request->empId)    
        ->orderBy('sd_orden.created_at', 'desc')
        ->first();

        $columns = $query ? array_keys($query->toArray()) : [];
        $columns = array_filter($columns, function ($column) {
            return $column !== 'empId'; // Columna a excluir
        });

        $columns = array_values($columns); // Reindexar el array si es necesa
        $filtros = $request['filter'];
        $filtros = json_decode(base64_decode($filtros));
        
       if(isset($filtros)){       
            $data     = SdOrden::query()       
                    ->filter($filtros)
                    ->join('sd_centro', 'sd_centro.centroId', '=', 'sd_orden.centroId')   
                    ->join('sd_centro_alm', 'sd_centro_alm.almId', '=', 'sd_orden.almId')   
                    ->where('sd_orden.empId', $request->empId)    
                    ->orderBy('sd_orden.created_at', 'desc')
                    ->get();
       }else{
            $data    = SdOrden::select('*')
                    ->join('sd_centro', 'sd_centro.centroId', '=', 'sd_orden.centroId')   
                    ->join('sd_centro_alm', 'sd_centro_alm.almId', '=', 'sd_orden.almId')   
                    ->where('sd_orden.empId', $request->empId)    
                    ->orderBy('sd_orden.created_at', 'desc')
                    ->take(1500)->get();
       }
       
        $resources = array(
                "data"   => $data,
                "colums" => $columns
        );
 
	
	  return response()->json($resources, 200); 
	
	
    }

    public function ins(Request $request){
     
        $name     = $request['name'];
        $empId    = $request['empId'];
        $id       = $request['id'];
        $centroId = $request['centro_id'];
        $almId    = $request['almacen_id'];
        $tipo     = $request['tipo'];
        
        $data     = json_encode($request->all());
        
        $valida = SdOrdeTemp::all()
                        ->where('empId', $empId)
                        ->where('centroId', $centroId)
                        ->where('almId', $almId)
                        ->where('ordtTip', $tipo)
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
                    'centroId' => $centroId,
                    'almId'    => $almId,
                    'ordtCustShortText1'=>$data ,
                    'ordtTip' => $tipo,
                    'ordtest'  => 'N', 
                ]);

                if (isset($affected)) {
                    $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes']);
                    dispatch($job); 
                    $job = new SdOrdenJobTemp($empId);         
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

    function ver(Request $request){
        $ordId = $request['ordId'];
        $data = SdOrdenDet::where('ordId', $ordId)->get();
        return response()->json($data, 200);
    }
}
