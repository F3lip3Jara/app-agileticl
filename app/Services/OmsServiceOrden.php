<?php
// app/Services/OmsService.php
namespace App\Services;

use App\Jobs\LogSistema;
use App\Models\Oms\LineaOrden;
use App\Models\Oms\OrdenWeb;
use Exception;


class OmsServiceOrden{

    public function __construct()
    {
        
    }

    public function manager_orden($json, $empId){

        $omsSerCliente  = new OmsServiceCliente;
        $valida         = OrdenWeb::all()->where('opedidExt', $json->id)->take(1); 
        $billing        = $json->billing;
        $cliId          = $omsSerCliente->regCliente($billing, $empId);
        $meta_data      = $json->meta_data;
        $opedPlace      = '';
        $opedEntrega    = '';
        $comCod         = '';
        $centroId       = 1;    
        $almId          = 1;
       
       try{
        foreach($meta_data as $item){
                
            if($item->key === "_billing_wooccm10"){
                    $opedEntrega = $item->value;
            }
            if($item->key === "_billing_place"){
                    $opedPlace = $item->value;
            }
            if($item->key === "_billing_wooccm11"){
                    $comCod = $item->value;
            }
        }
       }catch(Exception $ex){

       }       

        if (sizeof($valida) > 0 && $cliId > 0) {

            foreach($valida as $item){

                $opedId = $item['opedId'];
                $affected = OrdenWeb::where('opedId', $opedId)->update(
                    [
                    
                    'cliId'                         =>$cliId,// Agregado cliId
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
                    'opedidExt'                     =>$json->id,
                    'opedComCod'                    =>$comCod,
                    'clasTipId'                     =>1
                    ]
                );
                $line_items = $json->line_items;

                $this->regCarrito($line_items,$opedId, $empId , $centroId , $almId);
               
                if (isset($affected)) {
                    $job = new LogSistema( 36 , 41 , 'root', 1 , "ACTUALIZACIÓN  DE PRODUCTO");
                    dispatch($job);            
                   
                } else {
                    $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR ACTUALIZACIÓN DE PRODUCTO");
                    dispatch($job);        
                }
            }
       
        }else{
           if($cliId > 0){
                    
                    $affected =OrdenWeb::create([
                        'empId'                         =>$empId,
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
                        'opedidExt'                     =>$json->id,
                        'opedComCod'                    =>$comCod,
                        'clasTipId'                     =>1
                    ]
                );
                $line_items = $json->line_items;
                $opedId     = $affected->id;

                $this->regCarrito($line_items,$opedId, $empId , $centroId , $almId);

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

    public function regCarrito($line_items, $opedId , $empId , $centroId , $almId){  
        $valida = LineaOrden::all()
                ->where('opedId', $opedId)
                ->take(1);

        $id     = 0;
        if (sizeof($valida) > 0) {

            $affectedx = LineaOrden::where('opedId', $opedId)->delete();

                foreach($line_items as $item){
                    $affected = LineaOrden::create([               
                        'empId'              => $empId,
                        'opedId'             => $opedId,
                        'opeddproductoId'    => $item->product_id,
                        'opeddnombreProducto'=> $item->name,
                        'opeddsubtotal'      => $item->subtotal,
                        'opeddtotal'         => $item->total,
                        'opeddcantidad'      => $item->quantity,
                        'almId'              => $almId,
                        'centroId'           => $centroId

                    ]);

                }   

                if (isset($affected)) {
                    $job = new LogSistema( 36 , 41 , 'root', 1 , "INGRESO DE DETALLE ORDEN");
                    dispatch($job);            
                
                } else {
                    $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR INGRESO DE DETALLE ORDEN");
                    dispatch($job);        
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
                    'opeddcantidad'=> $item->quantity,
                    'almId'              => $almId,
                    'centroId'           => $centroId
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