<?php

use App\Http\Controllers\Sd\AlmacenController;
use App\Http\Controllers\Sd\CentroController;
use App\Http\Controllers\Sd\ClassTipController;
use App\Http\Controllers\SdOrdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

 //Centro
 Route::get('trabCentro'       , [CentroController::class,'index']);
 Route::post('insCentro'       , [CentroController::class,'ins']);
 Route::post('delCentro'       , [CentroController::class,'del']);
 Route::post('updCentro'       , [CentroController::class,'update']);


 //Almacen 
 Route::get('trabAlmacen'       , [AlmacenController::class,'index']);
 Route::post('insAlmacen'       , [AlmacenController::class,'ins']);
 Route::post('delAlmacen'       , [AlmacenController::class,'del']);
 Route::post('updAlmacen'       , [AlmacenController::class,'update']);
 
 Route::get('trabSdOrden'       , [SdOrdController::class,'index']);
 Route::post('insSdOrden'       , [SdOrdController::class,'ins']);


 Route::get('trabSdClass'       , [ClassTipController::class,'index']);
 Route::post('insSdClass'       , [ClassTipController::class,'ins']);
 Route::post('updSdClass'       , [ClassTipController::class,'update']);

?>