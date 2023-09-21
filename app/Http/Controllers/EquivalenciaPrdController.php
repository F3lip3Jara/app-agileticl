<?php

namespace App\Http\Controllers;

use App\Models\EquivalenciaPrd;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

set_time_limit(360);

class EquivalenciaPrdController extends Controller
{
    public function index( Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                $id = $item->id;
            }

            if($id > 0 )
            {
                return EquivalenciaPrd::select('producto.idPrd' , 'prdCod', 'equiPrdBulto', 'equiPrdBins','equiBultPallet')
                ->join('producto', 'prd_equivalencia.idPrd', '=', 'producto.idPrd')->get();
            }else{
                return response()->json('error' , 203);
            }
        }
    }

    public function ins(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                if($item->activado = 'A'){
                    $id = $item->id;
                }
            }
            if($id > 0 ){
                $data    = $request->all();
                $objIns  = $data['equiOk'];
                $objUp   = $data['equiAct'];

                if(isset($objIns)){
                    foreach($objIns as $item){
                        $affected = EquivalenciaPrd::create([
                                'empId'         => 1,
                                'idPrd'         => $item['idPrd'],
                                'equiPrdBulto'  => $item['unidad_bulto'],
                                'equiBultPallet'=> $item['bultos_pallet'],
                                'equiPrdBins'   => $item['unidad_bins']
                            ]);
                    }

                }

                if(isset($objUp)){
                   foreach($objUp as $item){
                        $affected = EquivalenciaPrd::where('idPrd' ,  $item['idPrd'])->update([
                                'equiPrdBulto' => $item['unidad_bulto'],
                                'equiBultPallet'=> $item['bultos_pallet'],
                            ]);
                    }
                }
               if( isset( $affected)){
                    $resources = array(
                        array("error" => "0", 'mensaje' => "Equivalencia ingresada manera correcta",
                        'type'=> 'success')
                        );
                    return response()->json($resources, 200);

                }else{
                    return response()->json('error' , 204);
                }
            }
        }
    }

    public function valEqui(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
           foreach($val as $item){
                if($item->activado = 'A'){
                    $id = $item->id;
                }
            }

            if($id > 0 ){
                $data   = $request->all();             
                $prd_er = array(); 
                $prd_ac = array(); 
                $prd_ok = array();
               
              

                foreach($data['detalle'] as $itemx){
                    
                     $valprod      = Producto::select('prdCod', 'idPrd')->where('prdCod', $itemx['cod_producto'])->get();
                     $cod_producto = $itemx['cod_producto'];
                     $unidad_bulto = $itemx['unidad_bulto'];
                     $bultos_pallet= $itemx['bultos_pallet'];
                     $unidad_bins  = $itemx['unidad_bins'];


                     if(count($valprod)> 0){
                     foreach($valprod as $producto){
                        if($producto['idPrd'] >0){
                            $valprod2 = EquivalenciaPrd::select('idPrd')->where('idPrd', $producto['idPrd'])->get();
                            if(count($valprod2)){
                              array_push( $prd_ac , array('idPrd'        => $producto['idPrd'] ,
                                                          'cod_producto' => $cod_producto,
                                                          'unidad_bulto' => $unidad_bulto,
                                                          'bultos_pallet'=> $bultos_pallet,
                                                          'unidad_bins'  => $unidad_bins
                                                ));
                            }else{
                                array_push( $prd_ok , array('idPrd'   => $producto['idPrd'] ,
                                                    'cod_producto' => $cod_producto,
                                                    'unidad_bulto' => $unidad_bulto,
                                                    'bultos_pallet'=> $bultos_pallet,
                                                    'unidad_bins'  => $unidad_bins
                                                ));

                                
                            }
                        }
                      }
                     }else{
                        array_push( $prd_er , array('idPrd'   => 0,
                                    'cod_producto' => $cod_producto,
                                    'unidad_bulto' => $unidad_bulto,
                                    'bultos_pallet'=> $bultos_pallet,
                                    'unidad_bins'  => $unidad_bins
                        ));
                     }
                 }
                 $resources = array(
                        array('totalEr' => count($prd_er), 
                              'totalOk' => count($prd_ok),
                              'totalAct'=> count($prd_ac),
                              'prd_er'  =>$prd_er,
                              'prd_ok'  =>$prd_ok,
                              'prd_ac'  =>$prd_ac
                        ));
                    return response()->json($resources, 200);

               
            }
        }
    }
    
    
    public function del(Request $request)
    {
     
    }

    public function valPrd(Request $request){

        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
           foreach($val as $item){
                if($item->activado = 'A'){
                    $id = $item->id;
                }
            }
            if($id > 0 ){
                $prdcod  = $request->prdCod;
                $valprod =Producto::select('prdCod', 'idPrd')->where('prdCod', $prdcod)->get();
                if(count($valprod)> 0){
                    foreach($valprod as $producto){
                       if($producto['idPrd'] >0){
                           $valprod2 = EquivalenciaPrd::select('idPrd')->where('idPrd', $producto['idPrd'])->get();
                           if(count($valprod2)){
                            $resources = "OK";
                            return response()->json($resources, 200);
                           }else{
                            $resources = "error";
                            return response()->json($resources, 200);
                           }
                        }
                    }
                }      
            }
        }
    }

  
  
}
