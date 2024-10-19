<?php 
namespace App\Services;

use App\Jobs\LogSistema;
use App\Models\Parametros\Clientes;

class OmsServiceCliente{

    public function __construct(){
        
    }
    
    public function regCliente($billing , $empId){      
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
                'empId'         => $empId,
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
}

?>