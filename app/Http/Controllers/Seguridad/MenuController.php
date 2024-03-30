<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Seguridad\MenuRol;
use App\Models\Seguridad\MenuSubModulo;
use App\Models\Seguridad\ModuleOpt;
use App\Models\Seguridad\ModuleRol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Ramsey\Collection\Collection;

class MenuController extends Controller
{
    public function index($empId , $rolId)
    {   

        $menu = ModuleRol::select('*')
            ->join('segu_modulo', 'segu_emp_mol_rol.molId', '=', 'segu_modulo.molId')
            ->where('segu_emp_mol_rol.rolId', $rolId)
            ->where('segu_emp_mol_rol.empId', $empId)
            ->orderBy('segu_emp_mol_rol.molId', 'asc')
            ->get();    

        $datos = [];

        $menu->groupBy('molId')->each(function ($items) use (&$datos , &$empId , &$rolId) {
            $modulo           = $items->first(); 
            $opciones         = $this->procesarOpciones( $modulo->molId, $empId , $rolId );  
            $mergedCollection = $opciones;
          
            $datos[] = [
               'molId'    => $modulo->molId,
                'rolId'    => $modulo->rolId,
                'molDes'   => $modulo->molDes,
                'molIcon'  => $modulo->molIcon,
                'opciones' => $mergedCollection
            ];
        });

        return $datos;
    }

    private function procesarOpciones($molId , $empId , $rolId ) {
        $opciones = MenuRol::select('*')
        ->where('rolId', $rolId)
        ->where('empId', $empId)
        ->where('molId', $molId)
        ->orderBy('optId', 'asc')
        ->get();   

    $menu = [];

    // Procesar opciones
    $opciones->each(function ($item) use (&$menu) {
        $data = [
            'optId'       => 0,
            'optDes'      => $item->optDes,
            'optLink'     => $item->optLink,
            'optSub'      => 'N',
            'molId'       => $item->molId,
            'childrens'   => [],
        ];

        $menu[] = $data;
    });

    // Procesar subopciones
    MenuSubModulo::select('*')
        ->where('rolId', $rolId)
        ->where('empId', $empId)
        ->where('molId', $molId)
        ->groupBy('molsId')
        ->get()
        ->each(function ($sub) use (&$menu) {
            $children = MenuSubModulo::select('*')
                ->where('rolId', $sub->rolId)
                ->where('empId', $sub->empId)
                ->where('molId', $sub->molId)
                ->where('molsId', $sub->molsId)
                ->get();

            $childrens = $children->map(function ($item) {
                return [
                    'name' => $item->optDes,
                    'url'  => $item->optLink,
                ];
            });

            $data = [
                'optId'       => 0,
                'optDes'      => $sub->molsDes,
                'optLink'     => $sub->molsDes,
                'optSub'      => 'S',
                'molId'       => $sub->molId,
                'childrens'   => $childrens->all(),
            ];

            $menu[] = $data;
        });

    return $menu;
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
