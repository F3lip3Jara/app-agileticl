<?php

namespace App\Http\Controllers;

use App\Models\BinCol;
use App\Models\BinsHist;
use App\Models\User;
use Illuminate\Http\Request;

class BinController extends Controller
{
    public function index( Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                $id = $item->id;
            }

            if($id > 0 )
            {   
                return  BinCol::select(['idColb',
                'colbnum',
                'bins_col.idEta',               
                'etaDes',            
                'colbtip'
                ])->join('etapasUser', 'etapasUser.idEta' , '=' , 'bins_col.idEta')->get();

            }else{
                return response()->json('error' , 203);
            }
        }
    }

    public function update(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();
        if($header == ''){
            return response()->json('error' , 203);
        }else{

            foreach($val as $item){
                if($item->activado = 'A'){
                    $id = $item->id;
                }
            }
            if($id > 0 ){

                $data = $request->all();

                foreach($data as $bins){
                    $colbnumnew =  $bins['colbnumnew'];                 
                    $bins    = $bins['bins'];                    
                }

                $affected = BinCol::where('idColb' , $bins['idColb'])->update([
                    'colbnum' =>   $colbnumnew
                    ]);

                if($affected > 0){

                        BinsHist::create([                            
                            'idColb'     => $bins['idColb'],
                            'empId'      => 1,
                            'idEta'      =>  $bins['idEta'],
                            'colbnum_h'  =>  $bins['colbnum'],
                            'colbtip_h'  =>  $bins['colbtip']
                        ]);


                    $resources = array(
                        array("error" => "0", 'mensaje' => "Correlativo actualizado manera correcta",
                        'type'=> 'success')
                        );
                    return response()->json($resources, 200);
                }else{
                    return response()->json('error', 204);
                }
            }else {
                    return response()->json('error' , 203);
            }
        }
    }


    public function getIdBin(Request $request){

        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                $id = $item->id;
            }

            if($id > 0 )
            {
                $colbnum = BinCol::select('colbnum')->take(1)->get();
                $bin     = $colbnum + 1;
                $affected = BinCol::where('idColb' , 1)->update([
                    'colbnum' =>   $bin
                ]);
                if($affected > 0){
                    $resources = array(
                        array("error" => "0", 'mensaje' => "Problemas en correlativo",
                        'type'=> 'success')
                        );
                }else{
                        return $bin;
                }

            }else{
                return response()->json('error' , 203);
            }
        }
    }
    public function verHist(Request $request)
    {
        $id     = 0;
        $header = $request->header('access-token');
        $val    = User::select('token' , 'id', 'activado')->where('token' , $header)->get();

        if($header == ''){
            return response()->json('error' , 203);
        }else{
            foreach($val as $item){
                if($item->activado = 'A'){
                    $id = $item->id;
                }
            }
            if($id > 0 ){
                $data   = $request->all();
                $idColb = $data['idColb'];
                $resources= BinsHist::select('*')->where( 'idColb' , $idColb)->get();                                
                 return response()->json($resources, 200);
                }else{
                    return response()->json('error', 204);
                }
            }
        }
}



