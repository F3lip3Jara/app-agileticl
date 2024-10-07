<?php

use App\Http\Controllers\Sd\AlmacenController;
use App\Http\Controllers\Sd\CentroController;
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
 

?>