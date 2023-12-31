<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\EtapaDes;
use App\Models\Maquinas;
use App\Models\User;
use Illuminate\Http\Request;

class EtapasController extends Controller
{
    public function index(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{

            foreach($val as $item){
                $id = $item->id;
            }
        if($id > 0 ){
            return Etapa::all();
        }else {
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
        }
        if($id > 0 ){
            $affected = Etapa::create(['etaDes' => $request->etaDes ,
                                       'etaProd'=> $request->etaProd]);

            if( isset( $affected)){
                $resources = array(
                    array("error" => "0", 'mensaje' => "Etapa ingresada manera correcta",
                    'type'=> 'success')
                    );
                return response()->json($resources, 200);

            }else{
                return response()->json('error' , 204);
            }

        }else {
                return response()->json('error' , 203);
        }
    }

    public function del(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::all(['id' , 'token' ])->where('token', $header );
        $xid    = $request->idEta;

        foreach($val as $item){
            if($item->activado = 'A'){
                $id = $item->id;
            }
        }

        if($id >0){
            $valida    = EtapaDes::all()->where('idEta' , $xid)->take(1);
            $validaMaq = Maquinas::all()->where('idEta' , $xid)->take(1);
            //si la variable es null o vacia elimino el rol
            if(sizeof($valida) > 0 || sizeof($validaMaq) > 0){
                  //en el caso que no se ecuentra vacia no puedo eliminar
                  $resources = array(
                    array("error" => "1", 'mensaje' => "La etapa no se puede eliminar",
                    'type'=> 'danger')
                    );
                   return response()->json($resources, 200);
            }else{
                $affected = Etapa:: where('idEta', $xid)->delete();

                if($affected > 0){
                    $resources = array(
                        array("error" => '0', 'mensaje' => "Etapa Eliminada Correctamente" ,'type'=> 'warning')
                        );
                    return response()->json($resources, 200);
                }else{
                    $resources = array(
                    array("error" => "2", 'mensaje' => "No se encuentra registro" ,'type'=> 'warning')
                    );
                    return response()->json($resources, 200);
                }
            }

        }else{
                return response()->json('error' , 203);
        }
    }
    public function update(Request $request)
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
            $affected = Etapa::where('idEta' , $request->idEta)
                              ->update(['etaDes' => $request->etaDes , 'etaProd' => $request->etaProd]);

            if($affected > 0){
                $resources = array(
                    array("error" => "0", 'mensaje' => "Etapa actualizada manera correcta",
                    'type'=> 'success')
                    );
                return response()->json($resources, 200);
            }else{
                return response()->json('error', 204);
            }
        }else {
                return response()->json('error' , 203);
        }
       }
    }

    public function index1(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $data   =explode(',', $request->get('etapas'));
        
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{

            foreach($val as $item){
                $id = $item->id;
            }
        if($id > 0 ){
            $etapas =  Etapa::Buscarpor('idEta', $data)->get();
            return response()->json($etapas , 200);

        }else {
            return response()->json('error' , 203);
        }
      }
    }

}
