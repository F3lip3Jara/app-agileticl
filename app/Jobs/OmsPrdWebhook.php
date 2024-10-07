<?php

namespace App\Jobs;

use App\Models\OMS\LineaOrden;
use App\Models\OMS\OrdenWeb;
use App\Models\Oms\WebhookOms;
use App\Models\Parametros\Clientes;
use App\Models\Parametros\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class OmsPrdWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
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

             if($obj->x_wc_webhook_topic == "product.updated"  || $obj->x_wc_webhook_topic == "product.created" ){
                $this->manager_prd($obj);
             }
             if($obj->x_wc_webhook_topic == "order.created"  || $obj->x_wc_webhook_topic == "order.updated"){
                $this->manager_orden($obj);
             }
        }
    }

    function manager_prd($obj){ 

        $json = json_decode($obj->json);
        $valida = Producto::all()->where('prdIdExt', $json->id)->take(1); 
        $sku = $this->generateSku( $json->sku , $json->name);
        $image = json_encode($json->images);
            
    
         if (sizeof($valida) > 0) {
            foreach($valida as $item){
                $affected = Producto::where('prdId', $item['prdId'])->update(
                    [
                        'prdCod' => $sku ,
                        'prdDes' => $json->name,
                        'prdObs' =>  $json->description,
                        'prdRap' => substr($sku, 0, 6),
                        'prdEan' => $sku ,
                        'prdTip' => 'T',
                        'prdCost'=>  $json->price,
                        'prdNet' =>  $json->price,
                        'prdBrut'=>  $json->price,
                        'prdInv'=> 'S',
                        'prdPes'=> 0,
                        'prdMin'=> 0,
                        'monId'=> 1,
                        'grpId'=> 1,
                        'grpsId'=> 1,
                        'unId'=> 1,
                        'colId'=> 1,
                        'empId'=> 1,
                        'prdIdExt'=>  $json->id,
                        'prdUrl'=>$image,
                        'prdMig' => 'S'
                    ]
                );
    
                $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                        'web_estado' => 'S'
                ]);
                if (isset($affected)) {
                    $job = new LogSistema( 36 , 41 , 'root', 1 , "ACTUALIZACIÓN  DE PRODUCTO");
                    dispatch($job);            
                   
                } else {
                    $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR ACTUALIZACIÓN DE PRODUCTO");
                    dispatch($job);        
                }
            }
           
        }else{
    
             $affected =Producto::create([
                     'prdCod' => $sku ,
                     'prdDes' => $json->name,
                     'prdObs' => $json->description,
                     'prdRap' => substr($sku, 0, 6),
                     'prdEan' => $sku ,
                     'prdTip' => 'T',
                     'prdCost'=>  $json->price,
                     'prdNet' =>  $json->price,
                     'prdBrut'=>  $json->price,
                     'prdInv'=> 'S',
                     'prdPes'=> 0,
                     'prdMin'=> 0,
                     'monId'=> 1,
                     'grpId'=> 1,
                     'grpsId'=> 1,
                     'unId'=> 1,
                     'colId'=> 1,
                     'empId'=> 1,
                     'prdIdExt'=>  $json->id,
                     'prdUrl'=>$image,
                     'prdMig' => 'S'
                 ]
             );
    
             $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                'web_estado' => 'S'
            ]);
    
             if (isset($affected)) {
                $job = new LogSistema( 36 , 41 , 'root', 1 , "INGRESO DE PRODUCTO");
                dispatch($job);            
               
            } else {
                $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR INGRESO DE PRODUCTO");
                dispatch($job);        
            }
         }
    
    }
    
    function generateSku($product , $namex)
    {
        // Verificar si el SKU es null
        
        
        if (is_null($product) || $product === "") {
        // Tomar el nombre del producto
        $name  = $namex;
        
        // Eliminar espacios del nombre, limitar a 13 caracteres y convertir a mayúsculas
        $generatedSku = strtoupper(substr(str_replace(' ', '', $name), 0, 13));
        
        // Devolver el SKU generado
        return $generatedSku;
        }
    
        // Si el SKU no es null, simplemente devolver el SKU existente
        return $product;
    }

    function manager_orden($obj){
    
                $empId  = 1;               
                $json   = json_decode($obj->json);
                $valida = OrdenWeb::all()->where('opedidExt', $json->id)->take(1); 
                $cliId  = $this->regCliente($json->billing);

                if (sizeof($valida) > 0) {
                    $meta_data      = $json->meta_data;
                    $opedPlace      = '';
                    $opedEntrega    = '';
                    
                    foreach($meta_data as $item){
                        
                        if($item->key === "_billing_wooccm10"){
                            $opedEntrega = $item->value;
                        }
                        if($item->key === "_billing_place"){
                            $opedPlace = $item->value;
                        }
                    }

                    foreach($valida as $item){
                        $opedId = $item['opedId'];
                        $affected = OrdenWeb::where('opedId', $opedId)->update(
                            [
                            
                            'cliId'                         => $cliId,// Agregado cliId
                            'opedparentid'                  =>$json->id,
                            'opedstatus'                    =>$json->status,
                            'opedmoneda'                    =>$json->currency,
                            'opedversion'                   =>$json->version,
                            'opedfechaCreacion'             =>$json->date_created,
                            'opedpreciosIncluyenImpuestos'  =>$json->prices_include_tax,
                            'opeddescuentoTotal'            =>$json->discount_total,
                            'opeddescuentoImpuesto'         =>$json->discount_tax,
                            'opedenvioTotal'                =>$json->shipping_total,
                            'opedenvioImpuesto'             =>$json->shipping_tax,
                            'opedimpuestoCarrito'           =>$json->cart_tax,
                            'opedtotal'                     =>$json->total,
                            'opedtotalImpuesto'             =>$json->total_tax,
                            'opedclaveOrden'                =>$json->order_key,      
                            'opedMetodoPago'                =>$json->payment_method,
                            'opedtituloMetodoPago'          =>$json->payment_method_title,
                            'opeddireccionIpCliente'        =>$json->customer_ip_address,
                            'opedEntrega'                   =>$opedEntrega,
                            'opedPlace'                     =>$opedPlace,
                            'userAgentCliente'              =>$json->customer_user_agent,
                            'opedcarritoHash'               =>$json->cart_hash,
                            'opedidExt'                     =>$json->id   
                            ]
                        );
                        $line_items = $json->line_items;

                        $this->regCarrito($line_items,$opedId, $empId);
                       
                        if (isset($affected)) {
                            $job = new LogSistema( 36 , 41 , 'root', 1 , "ACTUALIZACIÓN  DE PRODUCTO");
                            dispatch($job);            
                           
                        } else {
                            $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR ACTUALIZACIÓN DE PRODUCTO");
                            dispatch($job);        
                        }
                    }
               
                }else{
                    
                    $meta_data      = $json->meta_data;
                    $opedPlace      = '';
                    $opedEntrega    = '';
                    

                    foreach($meta_data as $item){
                        
                        if($item->key === "_billing_wooccm10"){
                            $opedEntrega = $item->value;
                        }
                        if($item->key === "_billing_place"){
                            $opedPlace = $item->value;
                        }
                    }
                 
                    $affected =OrdenWeb::create([
                        'empId'                         =>1,
                        'cliId'                         => $cliId,// Agregado cliId
                        'opedparentid'                  =>$json->id,
                        'opedstatus'                    =>$json->status,
                        'opedmoneda'                    =>$json->currency,
                        'opedversion'                   =>$json->version,
                        'opedfechaCreacion'             =>$json->date_created,
                        'opedpreciosIncluyenImpuestos'  =>$json->prices_include_tax,
                        'opeddescuentoTotal'            =>$json->discount_total,
                        'opeddescuentoImpuesto'         =>$json->discount_tax,
                        'opedenvioTotal'                =>$json->shipping_total,
                        'opedenvioImpuesto'             =>$json->shipping_tax,
                        'opedimpuestoCarrito'           =>$json->cart_tax,
                        'opedtotal'                     =>$json->total,
                        'opedtotalImpuesto'             =>$json->total_tax,
                        'opedclaveOrden'                =>$json->order_key,      
                        'opedMetodoPago'                =>$json->payment_method,
                        'opedtituloMetodoPago'          =>$json->payment_method_title,
                        'opeddireccionIpCliente'        =>$json->customer_ip_address,
                        'opedEntrega'                   =>$opedEntrega,
                        'opedPlace'                     =>$opedPlace,
                        'userAgentCliente'              =>$json->customer_user_agent,
                        'opedcarritoHash'               =>$json->cart_hash,
                        'opedidExt'                     =>$json->id
                    ]
                );
                $line_items = $json->line_items;
                $opedId      = $affected->id;

                $this->regCarrito($line_items,$opedId, $empId);
                       

                if (isset($affected)) {
                   $job = new LogSistema( 36 , 41 , 'root', 1 , "INGRESO DE ORDEN WEB");
                   dispatch($job);            
                  
               } else {
                   $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR INGRESO DE ORDEN WEB");
                   dispatch($job);        
               }
        }
    }

    public function regCliente($billing){      
        $valida = Clientes::all()->where('cliemail', $billing->email)->take(1);
        $id     = 0; 

        if (sizeof($valida) > 0) {
            foreach($valida as $item){
                $id = $item['cliId'];
                $affected = Clientes::where('cliId', $item['cliId'])->update(
                    [
                        'empId'         => 1,
                        'cliemail'      => $billing->email,
                        'clinombre'     => $billing->first_name,
                        'cliapellido'   => $billing->last_name,
                        'cliempresa'    => $billing->company,
                        'clidireccion_1'=> $billing->address_1,
                        'clidireccion_2'=> $billing->address_2,
                        'cliciudad'     => '',
                        'clicomuna'     =>'',
                        'clipais'       =>$billing->country,
                        'clitelefono'   =>$billing->phone,
                        'cliidExt'      =>0
                    ]
                );

              
                if (isset($affected)) {
                    $job = new LogSistema( 36 , 41 , 'root', 1 , "ACTUALIZACIÓN  DE CLIENTE");
                    dispatch($job);            
                   
                } else {
                    $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR ACTUALIZACIÓN DE CLIENTE");
                    dispatch($job);        
                }
            }

        }else{
            $affected =Clientes::create([               
                'empId'         => 1,
                'cliemail'      => $billing->email,
                'clinombre'     => $billing->first_name,
                'cliapellido'   => $billing->last_name,
                'cliempresa'    => $billing->company,
                'clidireccion_1'=> $billing->address_1,
                'clidireccion_2'=> $billing->address_2,
                'cliciudad'     => '',
                'clicomuna'     =>'',
                'clipais'       =>$billing->country,
                'clitelefono'   =>$billing->phone,
                'cliidExt'      =>0
            ]);

            $id = $affected->id;
            
            if (isset($affected)) {
                $job = new LogSistema( 36 , 41 , 'root', 1 , "INGRESO DE CLIENTE");
                dispatch($job);            
               
            } else {
                $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR INGRESO DE CLIENTE");
                dispatch($job);        
            }
        }
        return $id;
    }
    
    public function regCarrito($line_items, $opedId , $empId){  
        $valida = LineaOrden::all()
                  ->where('opedId', $opedId)
                  ->take(1);
        $id     = 0;

      

        if (sizeof($valida) > 0) {
                foreach($line_items as $item){
                    $affected = LineaOrden::where('opedId', $opedId)
                    ->where('opeddproductoId', $item->product_id)
                    ->update(
                        [
                            'opeddnombreProducto'=> $item->name,
                            'opeddsubtotal' => $item->subtotal,
                            'opeddtotal' => $item->total,
                            'opeddcantidad'=> $item->quantity
                        ]
                    );

                }    
        }else{
            foreach($line_items as $item){
                $affected = LineaOrden::create([               
                    'empId'         => $empId,
                    'opedId'        => $opedId,
                    'opeddproductoId'=> $item->product_id,
                    'opeddnombreProducto'=> $item->name,
                    'opeddsubtotal'=> $item->subtotal,
                    'opeddtotal'=> $item->total,
                    'opeddcantidad'=> $item->quantity
                ]);
            }   
            if (isset($affected)) {
                $job = new LogSistema( 36 , 41 , 'root', 1 , "INGRESO DE DETALLE ORDEN");
                dispatch($job);            
               
            } else {
                $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR INGRESO DE DETALLE ORDEN");
                dispatch($job);        
            }
        }
        
    }
}
