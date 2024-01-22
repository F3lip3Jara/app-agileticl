<?php

namespace App\Http\Controllers\Seguridad;
use App\Http\Controllers\Controller;
use App\Models\Seguridad\Module;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function index(Request $request)
    {   
        $empId=  $request['empId'];
        $datos = Module::select('*')->where('empId', $empId)->get();
        return response()->json($datos, 200);
    }

}
