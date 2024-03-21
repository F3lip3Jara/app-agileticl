<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Models\Parametros\Moneda;
use App\Models\Parametros\MonedaConversion;
use App\Models\Parametros\Producto;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class MonedaController extends Controller
{
    public function index(Request $request)
    {

        return Moneda::select('*')->get();
    }

    public function update(Request $request)
    {
        $name        = $request['name'];
        $empId       = $request['empId'];
        
        $affected = Moneda::where('monId', $request->monId)->update(
            [
                'monCod'   => $request->monCod,
                'monDes'   => $request->monDes,
                'monIntVal'=> $request->monIntVal,
                'monInt'   => $request->monInt,
                'monIntArray'=> $request->monIntArray,
             ]
        );

        if ($affected > 0) {
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accetaDes']);
            dispatch($job);            
            $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }

    public function ins(Request $request)
    {
        $name        = $request['name'];
        $empId       = $request['empId'];

        $affected = Moneda::create([
            'monCod'     => $request->monCod,
            'monDes'     => $request->monDes,
            'monIntVal'  => $request->monIntVal,
            'monInt'     => $request->monInt,
            'monIntArray'=> $request->monIntArray,
            'empId'      => 1
        ]);

        if (isset($affected)) {
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accetaDes']);
            dispatch($job);            
            $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }

    public function del(Request $request)
    {
        $xid         = $request->monId;
        $name        = $request['name'];
        $empId       = $request['empId'];

        $valida = Producto::all()->where('monId', $xid)->take(1);
        //si la variable es null o vacia elimino el rol
        if (sizeof($valida) > 0) {
            //en el caso que no se ecuentra vacia no puedo eliminar
            $resources = array(
                array(
                    "error" => "1", 'mensaje' => "La Moneda no se puede eliminar",
                    'type' => 'danger'
                )
            );
            return response()->json($resources, 200);
        } else {
            $affected = Moneda::where('monId', $xid)->delete();

            if ($affected > 0) {
                $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accetaDes']);
                dispatch($job);            
                $resources = array(
                    array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
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

    public function valMonCod(Request $request)
    {

        $data   = request()->all();
        $monCod   = $data['monCod'];
        $val    = Moneda::select('monCod')->where('monCod', $monCod)->get();
        $count  = 0;

        foreach ($val as $item) {
            $count = $count + 1;
        }

        return response()->json($count, 200);
    }

    public function indicadores(){     
        $hoy = Carbon::now('America/Santiago');        
        $valida = MonedaConversion::all()                 
                ->where('moncFecha', $hoy->format('Y-m-d'));

        if (sizeof($valida) > 0) {
            return MonedaConversion::select('*')
            ->join('parm_moneda', 'parm_moneda.monId', '=', 'parm_moneda_conversion.monId')
            ->where('moncFecha', $hoy->format('Y-m-d'))->get();
        } else {
            $data = Moneda::all()->where('monInt', 'S');
           
            foreach($data as $item){
                $client = new Client([
                    'base_uri'        => 'https://api.cmfchile.cl',
                    'verify'          => false, 
                    'headers'         => [
                     //Your header here
                ]
              ]);           
                try{
                    $url = 'https://api.cmfchile.cl/api-sbifv3/recursos_api/'.$item['monIntVal'].'?apikey=80e3f542faaf21efc24dd8111aca2eeb7dd28b28&formato=json';                    
                    $response   = $client->request('GET', $url);
                    $statusCode = $response->getStatusCode();               
                    $arr        = $item['monIntArray']; 
                 
                    if ($statusCode === 200) {
                        $data = json_decode($response->getBody()->getContents(), true);
                        if (isset($data[$arr]) && !empty($data[$arr])) {                        
                            $valor = str_replace('.', '', $data[$arr][0]['Valor']); // Elimina los puntos separadores de miles
                            $valor = str_replace(',', '.', $valor); // Reemplaza la coma por un punto para el decimal
                            $fecha = $data[$arr][0]['Fecha'];                        
                            $affected = MonedaConversion::create([                            
                                'monId' => $item['monId'],
                                'moncFecha' =>$fecha ,
                                'moncValor'=>$valor
                            ]);
                           
                    }else{
                        return 'Error en array';
                    }
            
                    } else {
                        return 'No se pudo obtener los datos. Código de estado: ' . $statusCode;
                    }
                    
                }catch(Exception $e){
                    return 'No se pudo obtener los datos. Código de estado: ' . $e;
                } 
            }

            return MonedaConversion::select('*')
            ->join('parm_moneda', 'parm_moneda.monId', '=', 'parm_moneda_conversion.monId')
            ->where('moncFecha', $hoy->format('Y-m-d'))->get();

        }
    }
}
