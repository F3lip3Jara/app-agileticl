<?php

namespace App\Http\Controllers\Oms;
use App\Http\Controllers\Controller;
use App\Jobs\OmsPrdWebhook;
use App\Models\Oms\OrdenWeb;
use App\Models\Oms\WebhookOms;
use App\Models\Sd\SdOrden;
use App\Models\Sd\SdOrdeTemp;
use App\Models\Seguridad\Empresa;
use App\Services\OmsServiceOrden;
use App\Services\OmsServiceProducto;

use Illuminate\Http\Request;
use function PHPUnit\Framework\isJson;

class WebhookController extends Controller
{

   


    public function __construct()
    {

    }


    public function ins(Request $request){
        $data = $request->all();
        $header = $request->header();
        
        $x_wc_webhook_event = $request->header('x-wc-webhook-event');
        $x_wc_webhook_resource =$request->header('x-wc-webhook-resource');
        $x_wc_webhook_topic = $request->header('x-wc-webhook-topic');
        
        
        $affected = WebhookOms::create([
            'json' => json_encode($data) ,
            'header'=>json_encode($header),
            'x_wc_webhook_event'=>$x_wc_webhook_event,
            'x_wc_webhook_resource'=>$x_wc_webhook_resource,
            'x_wc_webhook_topic'=>$x_wc_webhook_topic,
            'session'=>'',
            'web_estado'=>'N'
        ]);
        $job = new OmsPrdWebhook();
        dispatch($job);            
       
        return response()->json('ok', 200);

    }

    public function carro(Request $request){
      

        return response()->json('ok', 200);

    }

    public function product(Request $request){
        $data = WebhookOms::select('*')
        -> where('web_estado', 'N')
        ->get();
        
        $omsServiceProducto = new OmsServiceProducto();
        $omsSerOrden        = new OmsServiceOrden();

        foreach($data as $obj){

            $header = json_decode($obj->header);
            $clave  ='x-wc-webhook-source';
            $url    = $header->$clave['0'];
            
            $empresa = Empresa::select('empId')->where('empTokenOMS', $url)->get();
            $empId = $empresa[0]['empId'];
          
            if($obj->x_wc_webhook_topic == "product.updated"  || $obj->x_wc_webhook_topic == "product.created" ){
             
              if (isJson($obj->json)) {
                $json = json_decode($obj->json);

                $omsServiceProducto->manejarProducto($json, $empId);

                $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                    'web_estado' => 'S'
                ]);
                    
            } else {
                    // Manejo de error o caso en que no sea ni array ni JSON
                    echo "No es ni array ni JSON válido";
            }        

             }
             if($obj->x_wc_webhook_topic == "order.created"  || $obj->x_wc_webhook_topic == "order.updated"){
                if (isJson($obj->json)) {
                    $json = json_decode($obj->json);
    
                    $omsSerOrden->manager_orden($json,$empId);
                      
                    $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                        'web_estado' => 'S'
                    ]);

                    } else {
                        // Manejo de error o caso en que no sea ni array ni JSON
                        echo "No es ni array ni JSON válido";
                    }
             }
         
        }
    
    }

    
}
