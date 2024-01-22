<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\UnidadMed;
use App\Models\User;
use Illuminate\Http\Request;


class UnidadMedidaController extends Controller
{
    public function index(Request $request)
    {

        return UnidadMed::all();
    }

    public function update(Request $request)
    {

        $affected = UnidadMed::where('idUn', $request->idUn)->update(
            [
                'unCod' => $request->unCod,
                'unDes' => $request->unDes

            ]
        );

        if ($affected > 0) {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "Unidad de Medida actualizada manera correcta",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }

    public function ins(Request $request)
    {

        $affected = UnidadMed::create([
            'unCod' => $request->unCod,
            'unDes' => $request->unDes,
            'empId'  => 1
        ]);

        if (isset($affected)) {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "Unidad de Medida ingresada de manera correcta",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }

    public function del(Request $request)
    {

        $xid    = $request->idUn;



        $valida = Producto::all()->where('idUn', $xid)->take(1);
        //si la variable es null o vacia elimino el rol
        if (sizeof($valida) > 0) {
            //en el caso que no se ecuentra vacia no puedo eliminar
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "La unidad de medida no se puede eliminar",
                    'type' => 'danger'
                )
            );
            return response()->json($resources, 200);
        } else {
            $affected = UnidadMed::where('idUn', $xid)->delete();

            if ($affected > 0) {
                $resources = array(
                    array("error" => '0', 'mensaje' => "Unidad de medida eliminado Correctamente", 'type' => 'warning')
                );
                return response()->json($resources, 200);
            } else {
                $resources = array(
                    array("error" => "2", 'mensaje' => "No se encuentra registro", 'type' => 'warning')
                );
                return response()->json($resources, 200);
            }
        }
    }

    public function valUnCod(Request $request)
    {

        $data   = request()->all();
        $unCod  = $data['unCod'];
        $val    = UnidadMed::select('unCod')->where('unCod', $unCod)->get();
        $count  = 0;

        foreach ($val as $item) {
            $count = $count + 1;
        }

        return response()->json($count, 200);
    }
}
