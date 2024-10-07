<?php

namespace Database\Seeders;

use App\Models\Seguridad\Acciones;
use Faker\Factory as Faker;
use App\Models\Parametros\BinCol;
use App\Models\Parametros\Ciudad;
use App\Models\Parametros\Color;
use App\Models\Parametros\Comuna;
use App\Models\Seguridad\Roles;
use App\Models\User;
use App\Models\Seguridad\Empresa;
use App\Models\Seguridad\EmpresaOpciones;
use App\Models\Parametros\Etapa;

use App\Models\Parametros\Grupo;
use App\Models\Parametros\Maquinas;
use App\Models\Seguridad\Module;
use App\Models\Seguridad\ModuleOpt;
use App\Models\Seguridad\ModuleRol;
use App\Models\Parametros\Moneda;
use App\Models\Parametros\MovRechazo;
use App\Models\Seguridad\Opciones;
use App\Models\Parametros\Pais;
use App\Models\Parametros\Producto;
use App\Models\Parametros\Proveedor;
use App\Models\Parametros\Region;
use App\Models\Seguridad\RolesModule;
use App\Models\Parametros\SubGrupo;
use App\Models\SubOpciones;
use App\Models\Parametros\UnidadMed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder 
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(  )
    {
        Empresa::create([
            'empId'   => 1,
            'empDes'  =>'Agileticl EIRL',
            'empDir'  =>'Av Altamirano 1419',
            'empRut'  =>'76.350.147-7',
            'empGiro' =>'Desarrollo software',
            'empFono' => '+569997551015',
            'empImg'  => 'data:image/png;base64,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'
          ]);


        $faker = Faker::create();
        
       
        Etapa::create([
                'empId'  =>1,
                'etaDes' => 'MEZCLA' ,
                'etaProd'=> 'S']);

        Etapa::create([
                'empId'  =>1,
                'etaDes' => 'EXTRUSIÓN' ,
                'etaProd'=> 'S']);

        Etapa::create([
                'empId'  =>1,
                'etaDes' => 'TERMOFORMADO' ,
                'etaProd'=> 'S']);
    
        Etapa::create([
                'empId'  =>1,
                'etaDes' => 'ENVASADO' ,
                'etaProd'=> 'S']);

        Etapa::create([
                'empId'  =>1,
                'etaDes' => 'INYECCIÓN' ,
                'etaProd'=> 'S']);

        Etapa::create([
                'empId'  =>1,
                'etaDes' => 'IMPRESIÓN' ,
                'etaProd'=> 'S']);


        Roles::create([
          'rolId' => 1,
          'rolDes' =>'SUPER',
          'empId'  => 1
        ]);
        
       
        User::create([
            'name'      => 'SUPER',
            'email'     => 'adm@contacto.cl',
            'rolId'     => 1,
            'activado'  => 'A',
            'imgName'   => '',
            'token'     => '',
            'password'  => bcrypt('admin'),
            'empId'     => 1
         ]);

       
        Moneda::create([
            'monCod' => 'CLP',
            'monDes' => 'PESO CHILENO',
            'empId'  => 1
        ]);

     

        Moneda::create([
            'monCod'     => 'US',
            'monDes'     => 'DOLAR',
            'empId'      => 1,
            'monIntVal'  => 'dolar',
            'monIntArray'=> 'Dolares',
            'monInt'     => 'S',
        ]);

        Moneda::create([
            'monCod'     => 'UF',
            'monDes'     => 'UF',
            'empId'      => 1,
            'monIntVal'  => 'uf',
            'monIntArray'=> 'UFs',
            'monInt'     => 'S',
        ]);

       

        UnidadMed::create([
            'empId' => 1,
            'unDes' =>'UNIDAD',
            'unCod' =>'UN'
        ]);

         //OPCIONES
         $json = file_get_contents("database/data_prd/Opciones.json");
         $data = json_decode($json);
         foreach ($data as $obj) {
             Opciones::create(array(                 
                 'optDes'   => $obj->optDes,
                 'optLink'  => $obj->optLink              
             ));
         }

         //Acciones 
         $json = file_get_contents("database/data_prd/Acciones.json");
         $data = json_decode($json);
         foreach ($data as $obj) {
             Acciones::create(array(                 
                 'accDes'     => $obj->accDes,
                 'accUrl'     => $obj->accUrl,
                 'accetaDes'  => $obj->accetaDes,
                 'acceVig'    => $obj->acceVig,  
                 'optId'      => $obj->optId,
                 'accType'    => $obj->accType,
                 'accMessage' => $obj->accMessage            
             ));
         }

    
        //Sub Opciones
       /* $json = file_get_contents("database/data_prd/Sub_Opt.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            SubOpciones::create(array(
                'optId'     => $obj->idOpt,
                'optSDes'   => $obj->optSDes, 
                'optSLink'  => $obj->optSLink              
            ));
        }
*/
        
        Module:: create([
            'empId'  => 1,
            'molDes' => 'Seguridad',
            'molIcon'=> 'cilShieldAlt'

        ]);

        ModuleOpt:: create([
            'molId' => 1,
            'empId' => 1,            
            'optId' => 2
        ]);

        ModuleOpt:: create([
            'molId' => 1,
            'empId' => 1,            
            'optId' => 3
        ]);

        ModuleOpt:: create([
            'molId' => 1,
            'empId' => 1,            
            'optId' => 4
        ]);

        ModuleOpt:: create([
            'molId' => 1,
            'empId' => 1,            
            'optId' => 5
        ]);

        ModuleOpt::create([
            'molId' => 1,
            'empId' => 1,            
            'optId' => 6
        ]);

      

        EmpresaOpciones::create([
            'empId' =>1,
            'optId' =>2
        ]);
        EmpresaOpciones::create([
            'empId' =>1,
            'optId' =>3
        ]);
        EmpresaOpciones::create([
            'empId' =>1,
            'optId' =>4
        ]);
        EmpresaOpciones::create([
            'empId' =>1,
            'optId' =>5
        ]);
        EmpresaOpciones::create([
            'empId' =>1,
            'optId' =>6
        ]);
        ModuleRol::create([
            'empId' => 1,
            'molId' => 1,
            'rolId' => 1
        ]);


  /*      $json = file_get_contents("database/data_prd/Menu.json");
        $data = json_decode($json);
    
        foreach ($data as $request) {
            ModuleOpt::create([          
                'empId'=>1,
                'idRol'=>$request->idRol,
                'idMol'=>$request->idMol,
                'idOpt'=>$request->idOpt
            ]); 
        }*/ 

    /*    $json = file_get_contents("database/data_prd/Maquina.json");
        $data = json_decode($json);

        foreach($data as $request){
            Maquinas::create(['idEta' => $request->idEta ,
                               'maqCod'=> $request->maqCod ,
                               'maqTip'=> '',
                               'maqDes'=> $request->maqDes ,
                               'empId'=> 1 ]);
        }

        $json = file_get_contents("database/data_prd/Motivo.json");
        $data = json_decode($json);

        foreach($data as $request){
            MovRechazo::create([
                'motDes' => $request->motDes,
                'empId'  =>1,
                'etaID'  => $request->idEta
            ]);
        }
*/
      

      
 
      //COLORES
      $json = file_get_contents("database/data_prd/Color.json");
      $data = json_decode($json);
      foreach ($data as $obj) {
          Color::create(array(
              'colCod' => $obj->ColCod,
              'colDes' => $obj->ColDes,
              'empId'  => 1                
          ));
      }


     //PAIS
     $json = file_get_contents("database/data_prd/Pais.json");
     $data = json_decode($json);
     foreach ($data as $obj) {
         Pais::create(array(
             'paiCod'     => $obj->Cod_Pais,
             'paiDes'     => $obj->Pais_Des,
             'empId'      => 1               
         ));
     }

     $json = file_get_contents("database/data_prd/Region.json");
     $data = json_decode($json);
 
     foreach ($data as $obj) {
         $idPai = Pais::select('paiId')->where('paiCod' , $obj->PaiCod)->get();
         $xidPai = 0;
         foreach($idPai as $item){
                 $xidPai = $item->paiId;
         }
         Region::create(array(
             'empId'  => 1,
             'paiId'  => $xidPai, 
             'regCod' => $obj->RegCod,
             'regDes' => $obj->RegDes
         ));
       }

       $json = file_get_contents("database/data_prd/Ciudad.json");
       $data = json_decode($json);
   
       foreach ($data as $obj) {
           $idPai = Region::select('parm_pais.paiId', 'parm_region.regId')
           ->join('parm_pais', 'parm_pais.paiId' , '=' , 'parm_region.paiId')
           ->where('paiCod' , $obj->PaiCod )
           ->where('regCod' , $obj->RegCod )
           ->get();

           $xidPai = 0;
           $idReg = 0;

           foreach($idPai as $item){
                   $xidPai = $item->paiId;
                   $idReg  = $item->regId;
           }
           Ciudad::create(array(
               'empId'  => 1,
               'paiId'  => $xidPai, 
               'regId'  => $idReg,
               'ciuCod' => $obj->CiuCod,
               'ciuDes' => $obj->CiuDes
           ));
         }
        
         $json = file_get_contents("database/data_prd/Comuna.json");
         $data = json_decode($json);
     
         foreach ($data as $obj) {
             $idPai = Ciudad::select('parm_pais.paiId', 'parm_region.regId', 'parm_ciudad.ciuId')
             ->join('parm_pais', 'parm_pais.paiId' , '=' , 'parm_ciudad.paiId')
             ->join('parm_region', 'parm_region.regId' , '=' , 'parm_ciudad.regId')           
             ->where('paiCod' , $obj->PaiCod )
             ->where('regCod' , $obj->RegCod )
             ->where('ciuCod' , $obj->CiuCod )
             ->get();

             $xidPai = 0;
             $idReg = 0;
             $idCiu = 0;
             foreach($idPai as $item){
                     $xidPai = $item->paiId;
                     $idReg  = $item->regId;
                     $idCiu  = $item->ciuId;
             }
             Comuna::create(array(
                 'empId'  => 1,
                 'paiId'  => $xidPai, 
                 'regId'  => $idReg,
                 'ciuId'  => $idCiu,
                 'comCod' => $obj->ComCod,
                 'comDes' => $obj->ComDes
             ));
           } 

           $json = file_get_contents("database/data_prd/Proveedor.json");
           $data = json_decode($json);
       
           foreach ($data as $request) {
          
      
          $comCod = strval($request->ComCod);
          $comCod = trim($comCod);
          $datos = Comuna::select('paiId', 'regId', 'ciuId', 'comId')
          ->where('comCod', $comCod )->get();
  
            
          foreach($datos as $item){
              $idPai = $item->paiId;
              $idReg = $item->regId;           
              $idCiu = $item->ciuId;
              $idCom = $item->comId;  
          }
          Proveedor::create([
              'empId'    => 1,
              'prvRut'   => $request->PRVRUT,
              'prvNom'   => $request->PrvNom,
              'prvNom2'  => $request->PrvNom2,
              'prvGiro'  => strval($request->PrvGiro),
              'prvDir'   => $request->PrvDir,
              'prvNum'   => $request->PrvNum,
              'prvTel'   => $request->PrvTel,
              'prvMail'  => $request->PrvMail,
              'prvCli'   => $request->prvCli,
              'prvPrv'   => $request->prvPrv,
              'paiId'    => $idPai,
              'regId'    => $idReg,
              'comId'    => $idCom,
              'ciuId'    => $idCiu,
              'prvAct'   => 'S'
           ]);         
          }
        
        //GRUPOS

        $json = file_get_contents("database/data_prd/Grupo.json");
        $data = json_decode($json);      
        foreach ($data as $request) {   
            $affected = Grupo::create([
                'grpCod' => $request->GrpCod,
                'grpDes' => $request->GrdDes,
                'empId'  =>1
            ]);        
        }

        //SUB GRUPO
        $json = file_get_contents("database/data_prd/SubGrupo.json");
        $data = json_decode($json);
    
        foreach ($data as $request) {
            $xgrpCod = $request->GrpCod;

            $datos = Grupo::select('grpId')->where('grpCod', $xgrpCod )->get();   
            
            foreach($datos as $item){
                $idGrp = $item->grpId;
            }

            SubGrupo::create([
                'grpId'   => $idGrp,
                'empId'   => 1,
                'grpsCod' => $request->GrpScod,
                'grpsDes' => $request->GrpSDes
            ]);
        }

        
        $faker = Faker::create();      
        $json = file_get_contents("database/data_prd/Productos.json");
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
            
            $moneda   = Moneda::select('monId')->where('monCod', $request->monCod)->get();
            
            foreach($moneda as $itemx){
                $idMon = $itemx->monId;
            }

            $grupos = SubGrupo::select('grpId' , 'grpsId')->where('grpScod',$request->grpScod)->get();
          
            foreach($grupos as $item){
                $idGrp    = $item->grpId;
                $idSubGrp = $item->grpsId;
            }

            $xcolcod = '';
            $xcolcod = strval($request->colCod);      
            
            $colores = Color::select('colId')->where('colCod',$xcolcod)->get();
            
            foreach($colores as $color){
                $idCol = $color->colId;        
            }      
     
            $unidad = UnidadMed::select('unId')->where('unCod',$request->unCod)->get();

            foreach($unidad as $item){
                $idUn = $item->unId;
            }

        }
           

            //views
 DB::unprepared(file_get_contents('database/sqlviews/create-view-template.sql'));  


  }
}
