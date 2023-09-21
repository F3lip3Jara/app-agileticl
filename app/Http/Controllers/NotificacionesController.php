<?php

namespace App\Http\Controllers;

use App\Jobs\ActualizaEstNotificacionJob;
use App\Models\Notificaciones;
use App\Models\NotificacionesDet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificacionesController extends Controller
{

    public $name = '';
    
    public function index(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol')->where('token' , $header)->get();
        $rol    = 0;

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                $id   = $item->id;
                $rol  = $item->idRol;
            }
          if($id > 0 ){
             
            $affect = Notificaciones::all();           
            return  $affect;

        
          }else{
            return response()->json('error' , 204);
        }
     } 
    }
    
    public function notificaciones(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol')->where('token' , $header)->get();
        $rol    = 0;

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                $id   = $item->id;
                $this->name = $item->name;
            }

         $notificaciones = DB::table('notificaciones')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('notificaciones_visualizaciones')
                    ->where('notvUso' , '<>', $this->name) 
                    ->whereRaw('notificaciones_visualizaciones.idNot   = notificaciones.idNot');
                    
            })->get();

            Log::info("pendientes". $notificaciones->where('notEst' , 'P')->count());
            
            if($notificaciones->where('notEst' , 'P')->count() > 0){
                $job = new ActualizaEstNotificacionJob($notificaciones->toArray());
                dispatch($job);
            }
           

          //  ActualizaEstNotificacionJob::dispatch($notificaciones);

            $resources = 
                array("contador"      => $notificaciones->count(),  
                    "pendiente"       => $notificaciones->where('notEst' , 'P')->count(),
                      'notificaciones'=> $notificaciones)
                ;
            return response()->json($resources , 200);
          
        }
    }   

   

    public function lecturaNot(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol' , 'name')->where('token' , $header)->get();
        $rol    = 0;  

        if($header == ''){
            return response()->json('error' , 203);
        }else{
       
            foreach($val as $item){
                $id   = $item->id;
                $namex = $item->name;
            }

            NotificacionesDet::create([
                    'empId'     =>1,
                    'notvUso'   =>$namex,
                    'idNot'     => $request->idNot
    
                ]);
                  
          

            return response()->json("ok", 200);
            }
    }

    public function lecturaNotAll(Request $request){
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado', 'idRol' , 'name')->where('token' , $header)->get();
        $rol    = 0;  

        if($header == ''){
            return response()->json('error' , 203);
        }else{
       
            foreach($val as $item){
                $id   = $item->id;
                $namex = $item->name;
            }

            $data = $request->all();

            foreach($data as $item){              
                NotificacionesDet::create([
                    'empId'     =>1,
                    'notvUso'   =>$namex,
                    'idNot'     => $item['idNot']
    
                ]);
                  
            }
          
        
            return response()->json("ok", 200);
            }
    }
}
