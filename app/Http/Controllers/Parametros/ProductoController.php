<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\User;
use App\Models\viewProductos;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        return viewProductos::all()->take(1000);
    }

    public function update(Request $request)
    {

        $affected = Producto::where('idPrd', $request->idPrd)->update([
            'prdEan'   => $request->prdEan,
            'prdCod'   => $request->prdCod,
            'prdDes'   => $request->prdDes,
            'prdObs'   => $request->prdObs,
            'prdRap'   => substr($request->prdCod, 0, 6),
            'prdTip'   => $request->prdTip,
            'prdCost'  => $request->prdCost,
            'prdNet'   => $request->prdNet,
            'prdBrut'  => $request->prdBrut,
            'prdInv'   => $request->prdInv,
            'prdPes'   => $request->prdPes,
            'prdMin'   => $request->prdMin,
            'idMon'    => $request->idMon,
            'idGrp'    => $request->idGrp,
            'idSubGrp' => $request->idSubGrp,
            'idUn'     => $request->idUn,
            'idCol'    => $request->idCol
        ]);

        if ($affected > 0) {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "Producto actualizado manera correcta",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
        }
    }

    public function ins(Request $request)
    {

        $affected = Producto::create([
            'prdEan'   => $request->prdEan,
            'prdCod'   => $request->prdCod,
            'prdDes'   => $request->prdDes,
            'prdObs'   => $request->prdObs,
            'prdRap'   => substr($request->prdCod, 0, 6),
            'prdTip'   => $request->prdTip,
            'prdCost'  => $request->prdCost,
            'prdNet'   => $request->prdNet,
            'prdBrut'  => $request->prdBrut,
            'prdInv'   => $request->prdInv,
            'prdPes'   => $request->prdPes,
            'prdMin'   => $request->prdMin,
            'idMon'    => $request->idMon,
            'idGrp'    => $request->idGrp,
            'idSubGrp' => $request->idSubGrp,
            'idUn'     => $request->idUn,
            'idCol'    => $request->idCol
        ]);

        if (isset($affected)) {
            $resources = array(
                array(
                    "error" => "0", 'mensaje' => "Producto ingresado manera correcta",
                    'type' => 'success'
                )
            );
            return response()->json($resources, 200);
        } else {
            return response()->json('error', 204);
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
        $idPrd = $data['idPrd'];
        return Producto::select('*')->where('idPrd', $idPrd)->get();
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
        
        return Producto::select('idPrd', 'prdCod', 'prdDes','prdNet', 'prdCost', 'producto.idMon','monCod')
            ->join('moneda', 'moneda.idMon' , '=' , 'producto.idMon')
            ->where('prdTip',  $prdTip)
            ->get();
    }

   
}
