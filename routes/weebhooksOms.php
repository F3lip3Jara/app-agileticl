<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('weebhooks_oms_wooecommerce', function(Request $request){
    return response()->json($request->session(), 200);
});
?>