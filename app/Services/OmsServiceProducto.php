<?php
// app/Services/OmsService.php
namespace App\Services;

use App\Jobs\LogSistema;
use App\Models\Parametros\Producto;

class OmsServiceProducto
{
    public function manejarProducto($json, $empId)
    {   
      
        $valida = Producto::all()->where('prdIdExt', $json['id'])->take(1); 
        $sku    = $this->generateSku( $json['sku'] , $json['name']);
        $image  = json_encode($json['image']['src']); 
    
    
         if (sizeof($valida) > 0) {
            foreach($valida as $item){
                $affected = Producto::where('prdId', $item['prdId'])->update(
                    [
                        'prdCod' => $sku ,
                        'prdDes' => $json['name'],
                        'prdObs' =>  $json['description'],
                        'prdRap' => substr($sku, 0, 6),
                        'prdEan' => $sku ,
                        'prdTip' => 'T',
                        'prdCost'=>  $json['price'],
                        'prdNet' =>  $json['price'],
                        'prdBrut'=>  $json['price'],
                        'prdInv'=> 'S',
                        'prdPes'=> 0,
                        'prdMin'=> 0,
                        'monId'=> 1,
                        'grpId'=> 1,
                        'grpsId'=> 1,
                        'unId'=> 1,
                        'colId'=> 1,
                        'empId'=> $empId,
                        'prdIdExt'=>  $json['id'],
                        'prdUrl'=>$image,
                        'prdMig' => 'S'
                    ]
                );
    
              /*  $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                        'web_estado' => 'S'
                ]);*/
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
                        'prdDes' => $json['name'],
                        'prdObs' =>  $json['description'],
                        'prdRap' => substr($sku, 0, 6),
                        'prdEan' => $sku ,
                        'prdTip' => 'T',
                        'prdCost'=>  $json['price'],
                        'prdNet' =>  $json['price'],
                        'prdBrut'=>  $json['price'],
                        'prdInv'=> 'S',
                        'prdPes'=> 0,
                        'prdMin'=> 0,
                        'monId'=> 1,
                        'grpId'=> 1,
                        'grpsId'=> 1,
                        'unId'=> 1,
                        'colId'=> 1,
                        'empId'=> $empId,
                        'prdIdExt'=>  $json['id'],
                        'prdUrl'=>$image,
                        'prdMig' => 'S'
                 ]
             );
    
           /*  $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                'web_estado' => 'S'
            ]);*/
    
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

}

?>