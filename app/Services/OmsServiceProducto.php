<?php
// app/Services/OmsService.php
namespace App\Services;

use App\Jobs\LogSistema;
use App\Models\Parametros\Producto;
use App\Models\Parametros\Talla;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;

class OmsServiceProducto
{
    public function manejarProducto($json, $empId , $name , $slug , $description, $type)
    {   
        if($type == 'V'){
            $name = $name. $json['name'];
        }else{
            $name =  $json['name'];
        }

        if( $type == 'V'){
            $descripcion = $description;
        }else{
            $descripcion = $json['description'];
        }

        if($type =='V'){
          $talla = Talla::select('*')->where('tallaCod', $json['name'])->get();
          $tallaId =$talla[0]->tallaId;
        }else{
            $tallaId = 1;
        }


        $valida = Producto::select('*')->where('prdIdExt', $json['id'])->get(); 
        $sku    = $this->generateSku( $json['sku'] , $name);
        $image  = $json['image']['src']; 
        $image  = stripslashes($image); // Elimina las barras invertidas
        $image  = str_replace('"', '', $image); // Elimina las comillas
        
         if (sizeof($valida) > 0) {
            foreach($valida as $item){
                $affected = Producto::where('prdId', $item['prdId'])->update(
                    [
                        'prdCod' => $sku ,
                        'prdDes' => $name,
                        'prdObs' => $descripcion,
                        'prdRap' => substr($sku, 0, 6),
                        'prdEan' => $sku,
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
                        'prdIdExt'=> $json['id'],
                        'prdUrl'=>$image,
                        'prdMig' => 'S',
                        'tallaId'=> $tallaId
                    ]
                );
    
              /*  $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                        'web_estado' => 'S'
                ]);*/
                if (isset($affected)) {
                    $job = new LogSistema( 36 , 41 , 'root', 1 , "ACTUALIZACIÓN  DE PRODUCTO", 'success');
                    dispatch($job);            
                   
                } else {
                    $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR ACTUALIZACIÓN DE PRODUCTO" , 'danger');
                    dispatch($job);        
                }
            }
           
        }else{
    
             $affected =Producto::create([
                'prdCod' => $sku ,
                'prdDes' => $name,
                'prdObs' => $descripcion,
                'prdRap' => substr($sku, 0, 6),
                'prdEan' => $sku,
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
                'prdIdExt'=> $json['id'],
                'prdUrl'=>$image,
                'prdMig' => 'S',
                'tallaId'=> $tallaId
                 ]
             );
    
           /*  $affected = WebhookOms::where('omshId', $obj->omshId)->update([
                'web_estado' => 'S'
            ]);*/
    
             if (isset($affected)) {
                $job = new LogSistema( 36 , 41 , 'root', 1 , "INGRESO DE PRODUCTO" , 'success');
                dispatch($job);            
               
            } else {
                $job = new LogSistema( 36 , 40 , 'root', 1 , "ERROR INGRESO DE PRODUCTO" , 'danger');
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