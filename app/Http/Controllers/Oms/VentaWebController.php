<?php

namespace App\Http\Controllers\Oms;

use App\Http\Controllers\Controller;
use App\Models\Oms\LineaOrden;
use App\Models\Oms\OrdenWeb;
use App\Models\OrdenVenta;
use Illuminate\Http\Request;

class VentaWebController extends Controller
{
    public function index(Request $request)
    {
        return OrdenWeb::select('*')
        ->join('parm_clientes', 'parm_clientes.cliId', '=', 'vent_ordenes.cliId')       
        ->orderBy('vent_ordenes.opedfechaCreacion', 'desc')
        ->get();   
    }


    public function update(Request $request)
    {   
      /*  $name        = $request['name'];
        $empId       = $request['empId'];

        $affected = Color::where('colId', $request->colId)->update(
            [
                'colCod' => $request->colCod,
                'colDes' => $request->colDes
            ]
        );

        if ($affected > 0) {
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes']);
            dispatch($job);            
            $resources = array(
               array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }*/
    }

    public function ins(Request $request)
    { 
      /*  $name        = $request['name'];
        $empId       = $request['empId'];

        $affected = Color::create([
            'colCod' => $request->colCod,
            'colDes' => $request->colDes,
            'empId'  => 1
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
        }*/
    }

    public function del(Request $request)
    {
        /*$name        = $request['name'];
        $empId       = $request['empId'];


        $xid    = $request->colId;
        $valida = Producto::all()->where('colId', $xid)->take(1);
        //si la variable es null o vacia elimino el rol
        if (sizeof($valida) > 0) {
            //en el caso que no se ecuentra vacia no puedo eliminar
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "El Color  no se puede eliminar",
                    'type' => 'danger'
                )
            );
            return response()->json($resources, 200);
        } else {
            $affected = Color::where('colId', $xid)->delete();

            if ($affected > 0) {
                $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes']);
                dispatch($job);            
                $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
                );
            } else {
                $resources = array(
                    array("error" => "2", 'mensaje' => "No se encuentra registro", 'type' => 'warning')
                );
                return response()->json($resources, 200);
            }
        }*/
    }

    public function lineas_pedidos(Request $request)
    {


        $affected = LineaOrden::select('*')
                    ->where('opedId', $request->opedId)
                    ->where('empId' , $request->empId)
                    ->get();
        return response()->json($affected, 200);
    }
}
