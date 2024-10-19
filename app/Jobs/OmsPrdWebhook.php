<?php

namespace App\Jobs;

use App\Models\Oms\WebhookOms;
use App\Models\Seguridad\Empresa;
use App\Services\OmsServiceOrden;
use App\Services\OmsServiceProducto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use function PHPUnit\Framework\isJson;

class OmsPrdWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     
    
    public function __construct()
    {
      
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
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
