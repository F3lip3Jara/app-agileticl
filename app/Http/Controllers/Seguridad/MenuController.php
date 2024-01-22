<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Seguridad\MenuRol;
use App\Models\Seguridad\ModuleOpt;
use App\Models\User;
use Illuminate\Http\Request;


class MenuController extends Controller
{
    public function index($empId , $rolId)
    {
        
        $menu = MenuRol::select('*')
            ->where('rolId', $rolId)
            ->where('empId', $empId)
            ->orderBy('optId', 'asc')
            ->get();
        
        $datos = [];
        
        $menu->groupBy('molId')->each(function ($items, $molId) use (&$datos) {
        $modulo = $items->first();    
        $opciones = $items->groupBy('optId')->map(function ($opts, $items) {
                $opcion = $opts->first();     
                return [
                    'optId'  => $opcion->optId,
                    'optDes' => $opcion->optDes,
                    'optLink'=> $opcion->optLink,
                    'optSub' => $opcion->optSub,
                    'molId'  => $opcion->molId
                ];
            })->values()->all();
        
            $datos[] = [
              
                    'molId'   => $modulo->molId,
                    'rolId'   => $modulo->rolId,
                    'molDes'  => $modulo->molDes,
                    'molIcon' => $modulo->molIcon,
                    'opciones' => $opciones
              
            ];
        });
        
        // Imprimir o retornar el resultado
        return $datos;
      

    }

    

    public function indexRolOpt(Request $request)
    {
        $datos = ModuleOpt::select('*')
            ->join('roles_opt', 'roles_mod_opt.idOpt', '=', 'roles_opt.idOpt')
            ->join('roles', 'roles_mod_opt.idRol', '=', 'roles.idRol')
            ->join('roles_module', 'roles_mod_opt.idMol', '=', 'roles_module.idMol')
            ->where('roles_mod_opt.empId', 1)
            ->get();
        return response()->json($datos, 200);
    }

   
}
