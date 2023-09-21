<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivosController extends Controller
{
    public function index(Request $request){
        $nombre = $request['archivo'];
        $tipo   = $request['tipo'];

       if($tipo == "termoformado"){
        $url = Storage::url("calidad_archivos/termoformado/".$nombre);
        $resources = array("url" =>$url);
        return response()->json($resources, 200);         
       }else{
            if($tipo == 'inyeccion'){
                $url = Storage::url("calidad_archivos/inyeccion/".$nombre);
                $resources = array("url" =>$url);
                return response()->json($resources, 200);       
            }else{
                $url = Storage::url("calidad_archivos/envasado/".$nombre);
                $resources = array("url" =>$url);
                return response()->json($resources, 200);       
            }
        }
        
    }
}
