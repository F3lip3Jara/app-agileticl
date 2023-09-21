<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use App\Models\Color;
use App\Models\Empresa;
use App\Models\Grupo;
use App\Models\Moneda;
use App\Models\Producto;
use App\Models\SubGrupo;
use App\Models\UnidadMed;
use Illuminate\Database\Seeder;


class Ini_Prd extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();      
        $json = file_get_contents("database/data_prd/Productos1.json");
        $data = json_decode($json);
    
        foreach ($data as $request) {

            /*'idMon'    => $request->idMon,
            'idGrp'    => $request->idGrp,
            'idSubGrp' => $request->idSubGrp,
            'idUn'     => $request->idUn,
            'idCol'    => $request->idCol
            */

            $idMon    = 0;
            $idGrp    = 0;
            $idSubGrp = 0;
            $idCol    = 0;
            $idUn     = 0;
            
            $moneda   = Moneda::select('idMon')->where('monCod', $request->monCod)->get();
            
            foreach($moneda as $itemx){
                $idMon = $itemx->idMon;
            }

            $grupos = SubGrupo::select('idGrp' , 'idSubGrp')->where('grpScod',$request->grpScod)->get();
          
            foreach($grupos as $item){
                $idGrp    = $item->idGrp;
                $idSubGrp = $item->idSubGrp;
            }

            $xcolcod = '';
            $xcolcod = strval($request->colCod);      
            
            $colores = Color::select('idCol')->where('colCod',$xcolcod)->get();
            
            foreach($colores as $color){
                $idCol = $color->idCol;        
            }      
     
            $unidad = UnidadMed::select('idUn')->where('unCod',$request->unCod)->get();

            foreach($unidad as $item){
                $idUn = $item->idUn;
            }


             //Productos
             Producto::create([
                'prdEan'   => $faker->ean13(),
                'prdCod'   => $request->prdCod,
                'prdDes'   => $request->prdDes,
                'prdObs'   =>  '',
                'prdRap'   => $request->prdRap,
                'prdTip'   => $request->prdTip,
                'prdCost'  => $request->prdCost,
                'prdNet'   => $request->prdNet,
                'prdBrut'  => $request->prdBrut,
                'prdInv'   => $request->prdInv,
                'prdPes'   => $request->prdPes,
                'prdMin'   => $request->prdMin,
                'idMon'    => $idMon,
                'idGrp'    => $idGrp,
                'idSubGrp' => $idSubGrp,
                'idUn'     => $idUn,
                'idCol'    => $idCol
            ]);
         }
    }
}
