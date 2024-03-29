<?php

namespace App\Http\Controllers;

use App\Models\OrdenProduccion;
use App\Models\OrdProDet;
use App\Models\Producto;
use App\Models\User;
use App\Models\viewOrdenProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrdenProdController extends Controller
{
    public function index(Request $request)
    {

        $affected =  viewOrdenProduccion::select('*')->get();
        return response()->json($affected, 200);
    }

    public function update(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token', 'id', 'activado')->where('token', $header)->get();
        if ($header == '') {
            return response()->json('error', 203);
        } else {

            foreach ($val as $item) {
                if ($item->activado = 'A') {
                    $id = $item->id;
                }
            }
            if ($id > 0) {
                /*  $affected = Pais::where('idPai' , $request->idPai)->update([
                    'paiCod' => $request->paiCod,
                    'paiDes' => $request->paiDes
                ]);

                if($affected > 0){
                    $resources = array(
                        array("error" => "0", 'mensaje' => "Pais actualizada manera correcta",
                        'type'=> 'success')
                        );
                    return response()->json($resources, 200);
                }else{
                    return response()->json('error', 204);
                } */
            } else {
                return response()->json('error', 203);
            }
        }
    }

    public function ins(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token', 'id', 'activado', 'name')->where('token', $header)->get();
        $data   = $request->all();

        foreach ($val as $item) {
            if ($item->activado = 'A') {
                $id      = $item->id;
                $orpUsrG = $item->name;
            }
        }

        foreach ($data as $item) {

            $fecha          = $item['orpFech'];
            $orpFech        = $fecha['year'] . '-' . $fecha['month'] . '-' . $fecha['day'];
            $ordenes        = $item['ordenes'];

            $affected       = OrdenProduccion::create([
                'empId'     => 1,
                'idPrv'     => $item['idPrv'],
                'orpidEta'  => $item['idEta'],
                'orpNumOc'  => $item['orpNumOc'],
                'orpFech'   => $orpFech,
                'orpUsrG'   => $orpUsrG,
                'orpObs'    => $item['orpObs'],
                'orpNumRea' => $item['orpNumRea'],
                'orpEst'    => 1,
                'orpEstPrc' => 1,
                'orpTurns'  => ''

            ]);

            /*  $idOrp = OrdenProduccion::select('idOrp')->where('orpNumRea',$item['orpNumRea'])->get();

                foreach($idOrp as $idOrpx){
                        $xid = $idOrpx->idOrp;
                }*/
            $xid = $affected['id'];

            foreach ($ordenes as $orddet) {
                OrdProDet::create([
                    'idOrp'       => $xid,
                    'empId'       => 1,
                    'orpdPrdCod'  => $orddet['prdCod'],
                    'orpdPrdDes'  => $orddet['prdDes'],
                    'orpdCant'    => $orddet['orpdCant'],
                    'orpdCantDis' => $orddet['orpdCantDis'],
                    'orpdTotP'    => $orddet['orpdTotP'],
                    'orpdObs'     => $orddet['orpdObs'],
                    'orpdidEta'   => $item['idEta']
                ]);
            }
            if (isset($affected)) {
                $resources = array(
                    array(
                        "error" => "0", 'mensaje' => "Orden ingresada manera correcta",
                        'type' => 'success'
                    )
                );
                return response()->json($resources, 200);
            } else {
                return response()->json('error', 204);
            }
        }
    }

    public function del(Request $request)
    {
        /* $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();
        $xid    = $request->idPai;



        foreach($val as $item){
            if($item->activado = 'A'){
                $id = $item->id;
            }
        }
        if($id >0){
            $valida = Region::all()->where('idPai' , $xid)->take(1);
            //si la variable es null o vacia elimino el rol
            if(sizeof($valida) > 0 ){
                  //en el caso que no se ecuentra vacia no puedo eliminar
                  $resources = array(
                    array("error" => "1", 'mensaje' => "El País  no se puede eliminar , asociado a Región",
                    'type'=> 'danger')
                    );
                   return response()->json($resources, 200);
            }else{

               $valida = Proveedor::all()->where('idPai', $xid)->take(1);

               if(sizeof($valida) > 0 ){

                $resources = array(
                    array("error" => "1", 'mensaje' => "El País  no se puede eliminar , asociado a Proveedor",
                    'type'=> 'danger')
                    );
                    return response()->json($resources, 200);
               }else{

                $valida = PrvDirDes::all()->where('idPai', $xid)->take(1);

                if(sizeof($valida) > 0 ){
                    //en el caso que no se ecuentra vacia no puedo eliminar
                   $resources = array(
                      array("error" => "1", 'mensaje' => "La Comuna no se puede eliminar , asociado a Dirección",
                      'type'=> 'danger')
                      );
                     return response()->json($resources, 200);
                }else{
                    $affected = Pais:: where('idPai', $xid)->delete();

                    if($affected > 0){
                        $resources = array(
                            array("error" => '0', 'mensaje' => "País Eliminado Correctamente" ,'type'=> 'warning')
                            );
                        return response()->json($resources, 200);
                    }else{
                        $resources = array(
                        array("error" => "2", 'mensaje' => "No se encuentra registro" ,'type'=> 'warning')
                        );
                        return response()->json($resources, 200);
                    }
                }

               }



            }

        }else{
                return response()->json('error' , 203);
        }*/
    }
    public function filopNumRea(Request $request)
    {

        $data   = request()->all();
        $resources = viewOrdenProduccion::select('*')->where('orden_produccion', $data['orpNumRea'])->get();
        if (isset($resources)) {
            return response()->json($resources, 200);
        } else {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "No se encuentra coincidencia",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        }
    }

    public function valCodNumRea(Request $request)
    {
        $header = $request->header('access-token');
        $val    = User::select('token', 'id', 'activado')->where('token', $header)->get();

        foreach ($val as $item) {
            if ($item->activado = 'A') {
                $id = $item->id;
            }
        }

        $data   = request()->all();
        $orpNumRea = $data['orpNumRea'];
        $val    = OrdenProduccion::select('orpNumRea')->where('orpNumRea', $orpNumRea)->get();
        $count  = 0;
        foreach ($val as $item) {
            $count = $count + 1;
        }

        return response()->json($count, 200);
    }

    public function opNumRea(Request $request)
    {

        return  OrdenProduccion::select('orpNumRea')->get();
    }


    public function OrdPDetDta(Request $request)
    {
                $data = $request->all();

                return  OrdProDet::select('*')->where('idOrp', $data['idOrp'])->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('ordenes_de_trabajos_det')
                        ->whereRaw('ord_produccion_det.idOrp     = ordenes_de_trabajos_det.orden_produccion')
                        ->whereRaw('ord_produccion_det.orpdPrdCod = ordenes_de_trabajos_det.ordtdPrdCod');
                })->get();
          
    }


    public function valPrdOrd(Request $request)
    {

        
                $data = $request->all();
                $affected = Producto::all()->where('prdCod', $data['prdCod'])->take(1);
                if (sizeof($affected) > 0) {
                    $error = 3;
                    return response()->json($error, 200);
                } else {
                    $error = 2;
                    return response()->json($error, 200);
                }
        
    }

    public function OrdPDet(Request $request)
    {
        
                $data = $request->all();

                return  OrdProDet::select('*')->where('idOrp', $data['idOrp'])->get();
        
    }
}
