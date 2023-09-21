<?php

use App\Events\MensajeEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    MensajeEvent::dispatch("asd");
    return view('welcome');
});
   /* Route::get('productos'      , 'App\Http\Controllers\ProductoController@index');
    Route::get('productos1'     , 'App\Http\Controllers\ProductoController@index1');
    Route::get('filter_prdDes'  , 'App\Http\Controllers\ProductoController@filtorCod');
    Route::post('updProducto'   , 'App\Http\Controllers\ProductoController@update');
    Route::post('productos'     , 'App\Http\Controllers\ProductoController@store');*/
    Route::post('log'           , 'App\Http\Controllers\UserController@authenticateUser');

    Route::post('insUser'       , 'App\Http\Controllers\UserController@ins_Users');
    Route::get('trabUsuarios'   , 'App\Http\Controllers\UserController@trabUsuarios');
    Route::get ('getUsuario'    , 'App\Http\Controllers\UserController@getUser');
    Route::post('up_Password'   , 'App\Http\Controllers\UserController@up_Password');
    Route::post('setUserSession', 'App\Http\Controllers\UserController@setUserSession');
    Route::get('valUsuario'     , 'App\Http\Controllers\UserController@valUsuario');
    Route::post('upUsuario'     , 'App\Http\Controllers\UserController@upUsuario');
    Route::get('getUsuarios'    , 'App\Http\Controllers\UserController@getUsuarios');
    Route::post('upUsuario2'    , 'App\Http\Controllers\UserController@upUsuario2');

    //Menu
    Route::get('usuarioMenu'    , 'App\Http\Controllers\MenuController@index');
    Route::get('trabModule'     , 'App\Http\Controllers\MenuController@indexMod');
    Route::post('insMol'     , 'App\Http\Controllers\MenuController@insModulo');
    Route::get('trabOpciones'   , 'App\Http\Controllers\MenuOptController@index');
   
    Route::post('insOpt'     , 'App\Http\Controllers\MenuController@insOpciones');
    Route::get('trabSubOpcion'  , 'App\Http\Controllers\MenuOptController@indexSOpt');
    Route::get('trabRolesMod'   , 'App\Http\Controllers\RolesModuloController@index');
    Route::get('trabRolOpt'   , 'App\Http\Controllers\MenuController@indexRolOpt');
    Route::post('insRolMod'     , 'App\Http\Controllers\RolesModuloController@ins');
    Route::post('insRolOpt'     , 'App\Http\Controllers\MenuController@insRolOpt');
    
    //Route::get('getUser'      , 'App\Http\Controllers\UserController@setUserSession');
    Route::get('Imprimir'       , 'App\Http\Controllers\Etiquetas@imprimir');

    Route::get('trabRoles'      , 'App\Http\Controllers\RolesController@index');
    Route::post('updRoles'      , 'App\Http\Controllers\RolesController@update');
    Route::post('insRoles'      , 'App\Http\Controllers\RolesController@ins');
    Route::post('delRoles'      , 'App\Http\Controllers\RolesController@del');


    Route::get('trabGerencia'   , 'App\Http\Controllers\GerenciaController@index');
    Route::post('updGerencia'   , 'App\Http\Controllers\GerenciaController@update');
    Route::post('insGerencia'   , 'App\Http\Controllers\GerenciaController@ins');
    Route::post('delGerencia'   , 'App\Http\Controllers\GerenciaController@del');



    Route::get('trabEtapas'     , 'App\Http\Controllers\EtapasController@index');
    Route::post('insEtapas'     , 'App\Http\Controllers\EtapasController@ins');
    Route::post('delEtapas'     , 'App\Http\Controllers\EtapasController@del');
    Route::post('updEtapas'     , 'App\Http\Controllers\EtapasController@update');
    Route::get('etapasProd'     , 'App\Http\Controllers\EtapasController@index1');


    Route::get('trabEtapasDet'  , 'App\Http\Controllers\EtapasDesController@index');
    Route::post('insEtapasDet'  , 'App\Http\Controllers\EtapasDesController@ins');
    Route::post('delEtapasDet'  , 'App\Http\Controllers\EtapasDesController@del');
    Route::post('updEtapasDet'  , 'App\Http\Controllers\EtapasDesController@update');

    Route::get('trabEmpresa'    , 'App\Http\Controllers\EmpresaController@index');
    Route::get('trabPais'       , 'App\Http\Controllers\PaisController@index');
    Route::post('insPais'       , 'App\Http\Controllers\PaisController@ins');
    Route::post('delPais'       , 'App\Http\Controllers\PaisController@del');
    Route::post('updPais'       , 'App\Http\Controllers\PaisController@update');
    Route::get('valCodPai'      , 'App\Http\Controllers\PaisController@valCodPai');

    Route::get('trabRegion'     , 'App\Http\Controllers\RegionController@index');
    Route::post('insRegion'     , 'App\Http\Controllers\RegionController@ins');
    Route::post('delRegion'     , 'App\Http\Controllers\RegionController@del');
    Route::post('updRegion'     , 'App\Http\Controllers\RegionController@update');
    Route::get('valCodReg'      , 'App\Http\Controllers\RegionController@valCodReg');
    Route::get('regPai'         , 'App\Http\Controllers\RegionController@indexFil');

    Route::get('trabCiudad'     , 'App\Http\Controllers\CiudadController@index');
    Route::post('insCiudad'     , 'App\Http\Controllers\CiudadController@ins');
    Route::post('delCiudad'     , 'App\Http\Controllers\CiudadController@del');
    Route::post('updCiudad'     , 'App\Http\Controllers\CiudadController@update');
    Route::get('valCodCiudad'   , 'App\Http\Controllers\CiudadController@valCodCiudad');
    Route::get('regCiu'         , 'App\Http\Controllers\CiudadController@indexFil');

    Route::get('trabComuna'     , 'App\Http\Controllers\ComunaController@index');
    Route::post('insComuna'     , 'App\Http\Controllers\ComunaController@ins');
    Route::post('delComuna'     , 'App\Http\Controllers\ComunaController@del');
    Route::post('updComuna'     , 'App\Http\Controllers\ComunaController@update');
    Route::get('valCodComuna'   , 'App\Http\Controllers\ComunaController@valCodComuna');
    Route::get('ciuCom'         , 'App\Http\Controllers\ComunaController@indexFil');

    Route::get('trabProveedor'   , 'App\Http\Controllers\ProveedorController@index');
    Route::post('insProveedor'   , 'App\Http\Controllers\ProveedorController@ins');
    Route::get('valPrvRut'       , 'App\Http\Controllers\ProveedorController@valPrvRut');
    Route::get('datPrv'          , 'App\Http\Controllers\ProveedorController@datPrv');
    Route::post('updProveedor'   , 'App\Http\Controllers\ProveedorController@update');
    Route::get('selCliente'      , 'App\Http\Controllers\ProveedorController@selCliente');

    Route::post('insPrvDes'      , 'App\Http\Controllers\PrvDirController@ins');
    Route::get('trabPrvDir'      , 'App\Http\Controllers\PrvDirController@index');
    Route::post('delPrvDir'      , 'App\Http\Controllers\PrvDirController@del');

    Route::get('trabMoneda'      , 'App\Http\Controllers\MonedaController@index');
    Route::post('insMoneda'      , 'App\Http\Controllers\MonedaController@ins');
    Route::post('delMoneda'      , 'App\Http\Controllers\MonedaController@del');
    Route::post('updMoneda'      , 'App\Http\Controllers\MonedaController@update');
    Route::get('valMonCod'       , 'App\Http\Controllers\MonedaController@valMonCod');

    Route::get('trabColor'       , 'App\Http\Controllers\ColorController@index');
    Route::post('insColor'       , 'App\Http\Controllers\ColorController@ins');
    Route::post('delColor'       , 'App\Http\Controllers\ColorController@del');
    Route::post('updColor'       , 'App\Http\Controllers\ColorController@update');
    Route::get('valColCod'       , 'App\Http\Controllers\ColorController@valColCod');

    Route::get('trabUnidad'      , 'App\Http\Controllers\UnidadMedidaController@index');
    Route::post('insUnidad'      , 'App\Http\Controllers\UnidadMedidaController@ins');
    Route::post('delUnidad'      , 'App\Http\Controllers\UnidadMedidaController@del');
    Route::post('updUnidad'      , 'App\Http\Controllers\UnidadMedidaController@update');
    Route::get('valUnCod'        , 'App\Http\Controllers\UnidadMedidaController@valUnCod');

    Route::get('trabMotivo'      , 'App\Http\Controllers\MovRechazoController@index');
    Route::get('motEta'        , 'App\Http\Controllers\MovRechazoController@index1');
    Route::post('insMotivo'      , 'App\Http\Controllers\MovRechazoController@ins');
    Route::post('delMotivo'      , 'App\Http\Controllers\MovRechazoController@del');
    Route::post('updMotivo'      , 'App\Http\Controllers\MovRechazoController@update');

    Route::get('trabGrupo'       , 'App\Http\Controllers\GrupoController@index');
    Route::post('insGrupo'       , 'App\Http\Controllers\GrupoController@ins');
    Route::post('delGrupo'       , 'App\Http\Controllers\GrupoController@del');
    Route::post('updGrupo'       , 'App\Http\Controllers\GrupoController@update');
    Route::get('valGrpCod'       , 'App\Http\Controllers\GrupoController@valGrpCod');

    Route::get('trabSubGrupo'    , 'App\Http\Controllers\SubGrupoController@index');
    Route::post('insSubGrupo'    , 'App\Http\Controllers\SubGrupoController@ins');
    Route::post('delSubGrupo'    , 'App\Http\Controllers\SubGrupoController@del');
    Route::post('updSubGrupo'    , 'App\Http\Controllers\SubGrupoController@update');
    Route::get('valCodSubGrp'    , 'App\Http\Controllers\SubGrupoController@valCodSubGrp');
    Route::get('subGrp'          , 'App\Http\Controllers\SubGrupoController@indexFil');

    Route::get('trabProducto'    , 'App\Http\Controllers\ProductoController@index');
    Route::post('insProducto'    , 'App\Http\Controllers\ProductoController@ins');
    Route::post('delProducto'    , 'App\Http\Controllers\ProductoController@del');
    Route::post('updProducto'    , 'App\Http\Controllers\ProductoController@update');
    Route::get('valPrdCod'       , 'App\Http\Controllers\ProductoController@valCodPrd');
    Route::get('valPrdEan'       , 'App\Http\Controllers\ProductoController@valEanPrd');
    Route::get('filPrdDes'       , 'App\Http\Controllers\ProductoController@filPrdDes');
    Route::get('prdDes'          , 'App\Http\Controllers\ProductoController@prdDes');
    Route::get('filPrdCod'       , 'App\Http\Controllers\ProductoController@filPrdCod');
    Route::get('datPrd'          , 'App\Http\Controllers\ProductoController@datPrd');
    Route::get('datPrdMtP'       , 'App\Http\Controllers\ProductoController@datPrdMtP');
    Route::get('prodTerm'        , 'App\Http\Controllers\ProductoController@prodTerm');
    Route::get('prodInsumo'      , 'App\Http\Controllers\ProductoController@prodInsumo');

    Route::get('trabOrden'       , 'App\Http\Controllers\OrdenProdController@index');
    Route::post('insOrd'         , 'App\Http\Controllers\OrdenProdController@ins');
    //Route::post('delGrupo'       , 'App\Http\Controllers\GrupoController@del');
    //Route::post('updGrupo'       , 'App\Http\Controllers\GrupoController@update');
    Route::get('valCodNumRea'    , 'App\Http\Controllers\OrdenProdController@valCodNumRea');
    Route::get('opNumRea'        , 'App\Http\Controllers\OrdenProdController@opNumRea');
    Route::get('filopNumRea'     , 'App\Http\Controllers\OrdenProdController@filopNumRea');
    Route::get('OrdPDetDta'      , 'App\Http\Controllers\OrdenProdController@OrdPDetDta');
    Route::get('valPrdOrd'       , 'App\Http\Controllers\OrdenProdController@valPrdOrd');
    Route::get('OrdPDet'         , 'App\Http\Controllers\OrdenProdController@OrdPDet');    

    

    //orden de trabajo
    Route::post('insOT'          , 'App\Http\Controllers\OrdenTrabController@ins');
    Route::get('trabOt'          , 'App\Http\Controllers\OrdenTrabController@index');
    Route::get('verOtIny'        , 'App\Http\Controllers\OrdenTrabController@verOtIny');
    Route::get('verOtTer'        , 'App\Http\Controllers\OrdenTrabController@verOtTer');
    Route::get('verOtImp'        , 'App\Http\Controllers\OrdenTrabController@verOtImp');
    Route::post('insOTT'         , 'App\Http\Controllers\OrdenTrabController@insOTT');
    Route::post('AprOt'         , 'App\Http\Controllers\OrdenTrabController@AprOt');
    
    //Termoformado
    Route::get('trabOtTermo'     , 'App\Http\Controllers\OrdenTrabController@indexTermo');
    Route::get('trabOtTermofil'  , 'App\Http\Controllers\OrdenTrabController@indexTermoFil');
    Route::get('termoformado'    , 'App\Http\Controllers\TermoformadoController@indexfil');  
    //Route::get('filotNumRea'     , 'App\Http\Controllers\OrdenTrabController@filopNumRea');
    Route::post('insTerm'        , 'App\Http\Controllers\TermoformadoController@ins');
    Route::post('insTermCierre'  , 'App\Http\Controllers\TermoformadoController@insTermCierre');
    Route::post('insTermCierreC' , 'App\Http\Controllers\TermoformadoController@insTermCierreC');
    Route::post('uploadArTer'    , 'App\Http\Controllers\TermoformadoController@insTermArcv');
    Route::post('delTermArcv'    , 'App\Http\Controllers\TermoformadoController@delArcv');
    Route::get('getTermArchivo'  , 'App\Http\Controllers\TermoformadoController@downloadFileTerm');
    Route::post('insTermDet'     , 'App\Http\Controllers\TermoformadoController@insTermDet');
    Route::post('delTermDes'     , 'App\Http\Controllers\TermoformadoController@delTermDes');
    Route::post('termPallet'     , 'App\Http\Controllers\TermoformadoController@termPallet');
    Route::post('termConf'       , 'App\Http\Controllers\TermoformadoController@termConf');
    Route::post('termRechazo'    , 'App\Http\Controllers\TermoformadoController@termRechazo');
    //Envasado
    Route::post('insEnv'         , 'App\Http\Controllers\EnvasadoController@ins');
    Route::post('upEnv'          , 'App\Http\Controllers\EnvasadoController@up');
    Route::post('insEnvDet'      , 'App\Http\Controllers\EnvasadoController@insEnvDet');
    Route::post('envConf'        , 'App\Http\Controllers\EnvasadoController@envConf');
    Route::post('envRechazo'     , 'App\Http\Controllers\EnvasadoController@envRechazo');
    Route::post('upEnvC'         , 'App\Http\Controllers\EnvasadoController@upEnvC');
    Route::get('valEnv'          , 'App\Http\Controllers\EnvasadoController@valEnv');
    Route::get('trabOtEnv'       , 'App\Http\Controllers\OrdenTrabController@indexEnvasado');  
    Route::get('envDet '         , 'App\Http\Controllers\EnvasadoController@envDet');  
    Route::post('uploadArEnv'    , 'App\Http\Controllers\EnvasadoController@uploadArEnv');
    Route::post('delEnvArcv'     , 'App\Http\Controllers\EnvasadoController@delArcv');

    //Inyección
    Route::get('trabOtInye'      , 'App\Http\Controllers\OrdenTrabController@indexInyeccion');
    Route::post('insIny'         , 'App\Http\Controllers\InyeccionController@ins');
    Route::post('insInyCierre'   , 'App\Http\Controllers\InyeccionController@insInyCierre');
    Route::get('inyeccion'       , 'App\Http\Controllers\InyeccionController@indexfil'); 
    Route::post('uploadArIny'    , 'App\Http\Controllers\InyeccionController@insInyArcv'); 
    Route::post('delInyArcv'     , 'App\Http\Controllers\InyeccionController@delArcv');
    Route::post('insInyCierreC'  , 'App\Http\Controllers\InyeccionController@insTermCierreC');
    Route::post('inyConf'        , 'App\Http\Controllers\InyeccionController@inyConf');
    Route::post('inyRechazo'     , 'App\Http\Controllers\InyeccionController@inyRechazo');


    Route::get('trabMaquinas'    , 'App\Http\Controllers\MaquinasController@index');
    Route::post('insMaquinas'    , 'App\Http\Controllers\MaquinasController@ins');
    Route::post('delMaquinas'    , 'App\Http\Controllers\MaquinasController@del');
    Route::post('updMaquinas'    , 'App\Http\Controllers\MaquinasController@update');
    Route::get('filEta'          , 'App\Http\Controllers\MaquinasController@filEta');


    Route::get('trabCalJul'     , 'App\Http\Controllers\CalendarioJulController@index');
    Route::post('insCalJul'     , 'App\Http\Controllers\CalendarioJulController@ins');
    Route::post('delCalJul'     , 'App\Http\Controllers\CalendarioJulController@del');
    Route::get('busUltAno'      , 'App\Http\Controllers\CalendarioJulController@busUltAno');
    Route::get('valCal'         , 'App\Http\Controllers\CalendarioJulController@valCal');
    Route::get('diaJul'         , 'App\Http\Controllers\CalendarioJulController@diaJul');

    Route::get('trabPrdEqui'    , 'App\Http\Controllers\EquivalenciaPrdController@index');
    Route::post('valEqui'       , 'App\Http\Controllers\EquivalenciaPrdController@valEqui');
    Route::post('insEquival'    , 'App\Http\Controllers\EquivalenciaPrdController@ins');
    Route::get('valEquiPrd'     , 'App\Http\Controllers\EquivalenciaPrdController@valPrd');

    Route::get('trabLogSys'     , 'App\Http\Controllers\LogController@index');
    Route::post('insLogSys'     , 'App\Http\Controllers\LogController@ins');

    Route::get('trabMezcla'     , 'App\Http\Controllers\MezclaController@index');
    Route::post('insMezcla'     , 'App\Http\Controllers\MezclaController@ins');
    Route::get('mezclaDet'      , 'App\Http\Controllers\MezclaController@mezclaDet');
    Route::post('confMezcla'    , 'App\Http\Controllers\MezclaController@confMezcla');
    Route::get('filLotSal'      , 'App\Http\Controllers\MezclaController@filLotSal');
    Route::post('rechaMezcla'   , 'App\Http\Controllers\MezclaController@rechaMezcla');
    Route::get('getSaca'        , 'App\Http\Controllers\MezclaController@getSaca');
    Route::get('getSacaBins'    , 'App\Http\Controllers\MezclaController@getSacaBins');
    Route::get('getBinsT'       , 'App\Http\Controllers\TermoformadoController@getBins');
    //Bin
    Route::get('trabBins'       , 'App\Http\Controllers\BinController@index');
    Route::post('upBins'        , 'App\Http\Controllers\BinController@update');
    Route::get('verBins'        , 'App\Http\Controllers\BinController@verHist');
  
    //Extusion
    Route::get('trabExtrusion'  , 'App\Http\Controllers\ExtrusionController@index');
    Route::post('insExtrusion'  , 'App\Http\Controllers\ExtrusionController@ins');
    Route::post('rechaExtru'    , 'App\Http\Controllers\ExtrusionController@rechaExtru');
    Route::post('insConfirma'   , 'App\Http\Controllers\ExtrusionController@insConfirma');
    Route::post('insConfirmaO'  , 'App\Http\Controllers\ExtrusionController@insConfirmaO');
    Route::post('insConfirmaC'  , 'App\Http\Controllers\ExtrusionController@insConfirmaC');
    Route::post('confExtru'     ,  'App\Http\Controllers\ExtrusionController@confExtru');
    Route::post('confExtruO'    ,  'App\Http\Controllers\ExtrusionController@confExtruO');
    Route::get('filLotSalExt'   , 'App\Http\Controllers\ExtrusionController@filLotSal');
    Route::get('extDet'         , 'App\Http\Controllers\ExtrusionController@extDet');
    Route::get('indexFil'       , 'App\Http\Controllers\ExtrusionController@indexFil');
    Route::get('extruDis'       , 'App\Http\Controllers\ExtrusionController@extruDis');


      //Inyección
      Route::get('trabOtImp'       , 'App\Http\Controllers\OrdenTrabController@indexImpresion');
      Route::post('insImp'         , 'App\Http\Controllers\ImpresionController@ins');
      Route::post('insImpCierre'   , 'App\Http\Controllers\ImpresionController@insImpCierre');
      Route::get('impresion'       , 'App\Http\Controllers\ImpresionController@indexfil'); 
      Route::post('insImpCierreC'  , 'App\Http\Controllers\ImpresionController@insImpCierreC');
      Route::post('impConf'        , 'App\Http\Controllers\ImpresionController@impConf');
      Route::post('impRechazo'     , 'App\Http\Controllers\ImpresionController@impRechazo');

      //Archivos
      Route::get('archivos'         ,'App\Http\Controllers\ArchivosController@index');
      
      //Notificaciones
      Route::get('trabNot'          ,'App\Http\Controllers\NotificacionesController@index');
      Route::get('notificaciones'   , 'App\Http\Controllers\NotificacionesController@notificaciones');
      Route::get('notcontador'      , 'App\Http\Controllers\NotificacionesController@notcontador');
      Route::post('lecturaNot'      , 'App\Http\Controllers\NotificacionesController@lecturaNot');
      Route::post('lecturaNotAll'   , 'App\Http\Controllers\NotificacionesController@lecturaNotAll');