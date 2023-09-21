<?php

namespace App\Http\Controllers;

use App\Models\MenuRol;
use App\Models\Module;
use App\Models\ModuleOpt;
use App\Models\Opciones;
use App\Models\RolesModule;
use App\Models\SubOpciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MenuController extends Controller
{
    public function index(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                $id  = $item->id;
                $rol = $item->idRol;                
            }
        if($id > 0 ){
            //$datos = Module::select('*')->where('idRol', $rol)->get();  4
          
            $menu     = MenuRol::select('*')->where('idRol', $rol)->orderBy('idMol', 'asc')->orderBy('optDes', 'asc')->get();
            $datos    = [];
            $modulos  = [];           
            $menuOpt  = [];
            $laterMenu=[];
           
               
            if(sizeof($menu) > 0 ){ 
                foreach($menu as $t) {
                    $val   = false;
                    $idMol = $t->idMol;
                    $result = [];    
                    
                    if(sizeof($modulos)>0){
                        foreach($modulos as $item){
                           $xid= $item->idMol;
                           if($idMol == $xid){
                                $val  = true;                              
                           }
                        }                        
                    }
                   
                    if($val ==false){                      
                        $id       = $t['idMol'];
                        $idOpt    = $t['idOpt'];                       
                        $valopt   = false;                        
                        array_push($modulos,$t);
                    }
                }
                //Modulos
                if(sizeof($modulos)){
                    foreach($modulos as $modulo){                       
                        $opciones   = [];                          
                        foreach($menu as $opcion){                          
                            $optSub =[];                        
                            if($modulo->idMol == $opcion->idMol){
                                $valopt = false;
                                if(sizeof($opciones)>0){                                                       
                                    foreach($opciones as $optx){
                                        if($opcion->idOpt == $optx['idOpt']){
                                            $valopt = true;                                                   
                                        }  
                                    }                                    
                                }
                                if($valopt==false){                                   
                                    if($opcion->optSub == 'S'){                                    
                                        foreach($menu as $subopt){
                                            if($subopt->idMol == $opcion->idMol && $subopt->idOpt == $opcion->idOpt && $subopt->optSub == 'S'){
                                               $valsubopt = false;
                                               $xoptSub   =[];                                             
                                               if(sizeof($optSub)>0){
                                                    foreach($optSub as $optsubx){                                                        
                                                        if($optsubx['optSDes'] == $subopt->optSDes){
                                                            $valsubopt = true;
                                                        }
                                                    }                                                   
                                               }
                                               if($valsubopt == false){
                                                $xoptSub = array('optSDes' =>$subopt->optSDes , 'optSLink'=>$subopt->optSLink);        
                                                array_push($optSub , $xoptSub);
                                               }
                                            }  
                                        }
                                    }
							       $opt    = array('idOpt' => $opcion['idOpt'], 'optDes' => $opcion['optDes'], 'optLink'=> $opcion['optLink'], 'optSub' => $opcion['optSub'] , 'sub'=>$optSub );                                     
                                    array_push($opciones , $opt);
                                }
                            }
                                
                             
                         
                        }//fin de busqueda opciones

                        //return $xopciones;
                        $menuOpt   = array('idMol'=>$modulo->idMol , 'idRol'=>$modulo->idRol , 'molDes'=>$modulo->molDes, 'molIcon'=>$modulo->molIcon);
                        $laterMenu = array("menu" => $menuOpt, "opciones" => $opciones);
                        array_push($datos , $laterMenu);
                    }                      
                    return response()->json($datos , 200);
                }
            }
         }else {
            return response()->json('error' , 203);
        }
      }
      
    }

    public function indexMod(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{

            foreach($val as $item){
                $id  = $item->id;
                $rol = $item->idRol;
                
            }
            if($id > 0 ){

                $datos = Module::all()->where('empId' , 1);
                return response()->json($datos , 200);

            }else{
                return response()->json('error' , 203);
            }
        }
    }

    public function indexRolOpt(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{

            foreach($val as $item){
                $id  = $item->id;
                $rol = $item->idRol;
                
            }
            if($id > 0 ){

                $datos = ModuleOpt::select('*')
                ->join('roles_opt', 'roles_mod_opt.idOpt','=','roles_opt.idOpt')
                ->join('roles', 'roles_mod_opt.idRol','=','roles.idRol')
                ->join('roles_module', 'roles_mod_opt.idMol','=','roles_module.idMol')
                ->where('roles_mod_opt.empId' , 1)
                ->get();
                return response()->json($datos , 200);

            }else{
                return response()->json('error' , 203);
            }
        }
    }

    public function insModulo(Request $request){
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
            $affected = Module::create([
                'molDes' => $request->molDes,
                'molIcon' => $request->molIcon,
                'empId'  =>1
            ]);

            if( isset( $affected)){
                $resources = array(
                    array("error" => "0", 'mensaje' => "Grupo ingresado manera correcta",
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
    }
    
    public function insOpciones(Request $request){
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
            $affected = Opciones::create([
                'optDes' => $request->optDes,
                'optLink' => $request->optLink,
                'optSub' => $request->optSub,
                'empId'  =>1
            ]);

            if( isset( $affected)){
                $resources = array(
                    array("error" => "0", 'mensaje' => "Opción ingresada manera correcta",
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
    }

    public function insRolOpt(Request $request){
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
                $affected = ModuleOpt::create([
                    'idOpt' => $request->idOpt,
                    'idMol' => $request->idMol,
                    'idRol' => $request->idRol,   
                    'empId'  =>1                
                ]);
    
                if( isset( $affected)){
                    $resources = array(
                        array("error" => "0", 'mensaje' => "Opción ingresada manera correcta",
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
        }
}
