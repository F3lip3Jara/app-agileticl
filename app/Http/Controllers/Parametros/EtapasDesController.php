<?php

namespace App\Http\Controllers;

use App\Models\EtapaDes;
use App\Models\LogSys;
use App\Models\User;
use Illuminate\Http\Request;


class EtapasDesController extends Controller
{
    public function index(Request $request)
    {

        $data = $request->all();
        return EtapaDes::select('*')
            ->join('parm_etapa', 'parm_etapa_log.etaId', '=', 'parm_etapa.etaId')
            ->where('parm_etapa_log.etaId', $data['etaId'])
            ->get();
    }

    public function ins(Request $request)
    {
        $affected = EtapaDes::create([
            'etaId' => $request->etaId,
            'etaDesDes' => $request->etaDesDes,
            'etaDesDel' => 'S'
        ]);

        if (isset($affected)) {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "Etapa ingresada manera correcta",
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

        $xid    = $request->etaDesId;
        // $valida = EtapaDes::all()->where('idEtaDes' , $xid)->take(1);
        //si la variable es null o vacia elimino el rol
        $valida = LogSys::all()->where('etaDesId',  $xid)->take(1);

        if (sizeof($valida) > 0) {
            //en el caso que no se ecuentra vacia no puedo eliminar
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "La etapa no se puede eliminar",
                    'type' => 'danger'
                )
            );
            return response()->json($resources, 200);
        } else {
            $affected = EtapaDes::where('etaDesId', $request->etaDesId)
                ->where('etaId', $request->etaId)
                ->delete();

            if ($affected > 0) {
                $resources = array(
                    array("error" => '0', 'mensaje' => "Etapa Eliminada Correctamente", 'type' => 'warning')
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
    public function update(Request $request)
    {

        $data = $request->all();
        $affected = EtapaDes::where('etaId', $data['etaId'])
            ->where('etaDesId', $data['etaDesId'])
            ->update(['etaDesDes' => $data['etaDesDes']]);


        if ($affected > 0) {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "Etapa actualizada manera correcta",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }
}
