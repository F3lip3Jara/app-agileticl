<?php

namespace App\Http\Controllers\Oms;

use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Jobs\OmsPrdWebhook;
use App\Models\OMS\LineaOrden;
use App\Models\OMS\OrdenWeb;
use App\Models\Oms\WebhookOms;
use App\Models\Parametros\Clientes;
use App\Models\Parametros\Producto;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
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

        foreach($data as $obj){

            if($obj->x_wc_webhook_topic == "order.created"  || $obj->x_wc_webhook_topic == "order.updated"){

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
