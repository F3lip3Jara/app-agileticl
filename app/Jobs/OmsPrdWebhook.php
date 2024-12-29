<?php

namespace App\Jobs;

use App\Models\Oms\WebhookOms;
use App\Models\Seguridad\Empresa;
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
        
       
        foreach($data as $obj){

            $header  = json_decode($obj->header);
            $clave   = 'x-wc-webhook-source';
            $url     = $header->$clave['0'];
            $empresa = Empresa::select('empId')->where('empTokenOMS', $url)->get();
            $empId   = $empresa[0]['empId'];

             // Dispatch el job correspondiente según el recurso
             if($obj->x_wc_webhook_topic == "product.updated"  || $obj->x_wc_webhook_topic == "product.created" ){
                dispatch(new OmsProductWebhook($empId));
            } else 
                if ($obj->x_wc_webhook_topic == "order.created"  || $obj->x_wc_webhook_topic == "order.updated") {
                    dispatch(new OmsOrderWebhook());
                }


           }
         

    }

}
