<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Jobs\ActualizaEstNotificacionJob;
use App\Models\Notificaciones;
use App\Models\NotificacionesDet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificacionesController extends Controller
{

   

    public function index(Request $request)
    {   
        $empId  = $request['empId'];
        $affect = Notificaciones::select('*')->where('empId', $empId)->get();
        return  $affect;
    }

    public function notificaciones(Request $request)
    {   
        $empId = $request['empId'];

        $notificaciones = DB::table('notificaciones')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('notificaciones_visualizaciones')
                    ->whereRaw('notificaciones_visualizaciones.idNot   = notificaciones.idNot');
            })->get();

        Log::info("pendientes" . $notificaciones
                                    ->where('notEst', 'P')                                    
                                    ->count());

        if ($notificaciones->where('notEst', 'P')->count() > 0) {
            $job = new ActualizaEstNotificacionJob($notificaciones->toArray());
            dispatch($job);
        }
        //  ActualizaEstNotificacionJob::dispatch($notificaciones);
        $resources =
            array(
                "contador"       => $notificaciones->count(),
                "pendiente"      => $notificaciones->where('notEst', 'P')->where('empId', $empId)->count(),
                'notificaciones' => $notificaciones
            );
        return response()->json($resources, 200);
    }

    public function lecturaNot(Request $request)
    {

        NotificacionesDet::create([
            'empId'     => 1,
            'notvUso'   => $request->name,
            'idNot'     => $request->idNot

        ]);
        return response()->json("ok", 200);
    }

    public function lecturaNotAll(Request $request)
    {
        $data = $request->all();
        foreach ($data as $item) {
            NotificacionesDet::create([
                'empId'     => 1,
                'notvUso'   => $data['name'],
                'idNot'     => $item['idNot']

            ]);
        }
        return response()->json("ok", 200);
    }
}
