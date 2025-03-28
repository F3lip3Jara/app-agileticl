<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Jobs\LogSistema;
use App\Models\Parametros\Producto;
use App\Models\Parametros\Moneda;
use App\Models\viewProductos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $table   = 'productos';
        $columns = Schema::getColumnListing($table);
        $filtros = $request['filter'];
        $filtros = json_decode(base64_decode($filtros));  
       if(isset($filtros)){ 
      
        $data     = viewProductos::query()->filter($filtros)->get();
       }else{
         $data    = viewProductos::all()->take(1000);
       }
       
        $resources = array(
                "data"   => $data,
                "colums" => $columns
        );
        return response()->json($resources, 200);    
    }

    public function update(Request $request)
    {
        $name        = $request['name'];
        $empId       = $request['empId'];   
        $data        = $request->all();
        $codigo_externo = $data['id_ext'];
        $url = $data['url'];
        if($codigo_externo == null){
            $codigo_externo = 0;
        }
        if($url == null){
            $url = '';
        }
       
        $monId = Moneda::where('monCod', $data['moneda'])->first()->monId;

        $affected = Producto::where('prdId', $data['id'])
        ->where('empId', $empId)
        ->update([
            'prdEan'   => $data['cod_barra'],
            'prdCod'   => $data['cod_pareo'],
            'prdDes'   => $data['descripcion'],
            'prdObs'   => $data['observaciones'],
            'prdRap'   => substr($data['cod_pareo'], 0, 6),
            'prdTip'   => $data['tipo']['code'],
            'prdCost'  => $data['costo'],
            'prdNet'   => $data['neto'],
            'prdBrut'  => $data['bruto'],
            'prdInv'   => $data['inventariable'],  
            'prdPes'   => $data['peso'],
            'prdMin'   => $data['minimo'],
            'monId'    => $monId,
            'grpId'    => $data['grupo']['id'],
            'grpsId'   => $data['sub_grupo']['id'],
            'unId'     => $data['medida']['id'],
            'colId'    => $data['color']['id'],
            'empId'    => $empId,
            'prdIdExt' => $codigo_externo,
            'prdUrl'   => $url,           
            'tallaId'  => $data['talla']['id'],
            'prdAncho' => $data['ancho'],
            'prdLargo' => $data['largo'],
            'prdAlto'  => $data['alto'],
            'prdPeso'  => $data['peso'],
            'prdVolumen' => $data['volumen']
        ]);
        if( isset( $affected)){
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes'], $request->log['0']['accTip']);
            dispatch($job); 
            $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        }else{
            return response()->json('error' , 204);
        }
    }

    public function ins(Request $request)
    {

        $name        = $request['name'];
        $empId       = $request['empId'];   
        $data        = $request->all();
        $codigo_externo = $data['id_ext'];
        $url = $data['url'];
        if($codigo_externo == null){
            $codigo_externo = 0;
        }
        if($url == null){
            $url = '';
        }
       
        $monId = Moneda::where('monCod', $data['moneda'])->first()->monId;


        $affected = Producto::create([
            'prdEan'   => $data['cod_barra'],
            'prdCod'   => $data['cod_pareo'],
            'prdDes'   => $data['descripcion'],
            'prdObs'   => $data['observaciones'],
            'prdRap'   => substr($data['cod_pareo'], 0, 6),
            'prdTip'   => $data['tipo']['code'],
            'prdCost'  => $data['costo'],
            'prdNet'   => $data['neto'],
            'prdBrut'  => $data['bruto'],
            'prdInv'   => $data['inventariable'],  
            'prdPes'   => $data['peso'],
            'prdMin'   => $data['minimo'],
            'monId'    => $monId,
            'grpId'    => $data['grupo']['id'],
            'grpsId'   => $data['sub_grupo']['id'],
            'unId'     => $data['medida']['id'],
            'colId'    => $data['color']['id'],
            'empId'    => $empId,
            'prdIdExt' => $codigo_externo,
            'prdUrl'   => $url,           
            'tallaId'  => $data['talla']['id'],
            'prdAncho' => $data['ancho'],
            'prdLargo' => $data['largo'],
            'prdAlto'  => $data['alto'],
            'prdPeso'  => $data['peso'],
            'prdVolumen' => $data['volumen']
        ]);

        if( isset( $affected)){
            $job = new LogSistema( $request->log['0']['optId'] , $request->log['0']['accId'] , $name , $empId , $request->log['0']['accDes'], $request->log['0']['accTip']);
            dispatch($job); 
            $resources = array(
                array("error" => '0', 'mensaje' => $request->log['0']['accMessage'], 'type' => $request->log['0']['accType'])
            );
            return response()->json($resources, 200);
        }else{
            return response()->json('error' , 204);
        }
    }

    public function del(Request $request)
    {
    }

    public function valCodPrd(Request $request)
    {
        $data   = request()->all();
        $prdCod = $data['prdCod'];
        $val    = Producto::select('prdCod')->where('prdCod', $prdCod)->get();
        $count  = 0;
        foreach ($val as $item) {
            $count = $count + 1;
        }
        return response()->json($count, 200);
    }

    public function valEanPrd(Request $request)
    {

        $data   = request()->all();
        $prdEan   = $data['prdEan'];
        $val    = Producto::select('prdEan')->where('prdEan', $prdEan)->get();
        $count  = 0;
        foreach ($val as $item) {
            $count = $count + 1;
        }

        return response()->json($count, 200);
    }

    public function filPrdDes(Request $request)
    {

        $data   = request()->all();
        $resources = viewProductos::select('*')->where('descripcion', $data['prdDes'])->get();

        if (isset($resources)) {
            return response()->json($resources, 200);
        } else {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "No se encuentra coincidencia",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        }
    }


    public function filPrdCod(Request $request)
    {

        $data   = request()->all();
        $resources = viewProductos::select(['id', 'cod_pareo', 'descripcion'])->where('cod_pareo', 'like', $data['prdCod'] . '%')->get()->take(10);

        if (isset($resources)) {
            return response()->json($resources, 200);
        } else {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "No se encuentra coincidencia",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        }
    }

    public function prdDes(Request $request)
    {

        return Producto::select('prdDes')->get();
    }

    public function datPrd(Request $request)
    {

        $data   = request()->all();
        $idPrd = $data['prdId'];
        return Producto::select('*')->where('prdId', $idPrd)->get();
    }

    public function datPrdMtP(Request $request)
    {

        return Producto::select('idPrd', 'prdCod', 'prdDes', 'prdTip')
            ->where('prdTip', 'M')
            ->orWhere('prdTip', '=', 'B')
            ->orWhere('prdTip', '=', 'V')
            ->get();
    }


    public function prod(Request $request)
    {
        $data = $request->all();
        $prdTip = $data['prdTip'];
        
        return Producto::select('prdId', 'prdCod', 'prdDes','prdNet', 'prdCost', 'parm_producto.monId','monCod')
            ->join('parm_moneda', 'parm_moneda.monId' , '=' , 'parm_producto.monId')
            ->where('prdTip',  $prdTip)
            ->get();
    }

   
}
