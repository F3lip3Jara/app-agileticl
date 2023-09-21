<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdenVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_pago', function (Blueprint $table) {
            $table->bigIncrements('idTipPag');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');        
            $table->string('tipCod');
            $table->string('tipDes');
            $table->timestamps();
        });

        Schema::create('ord_venta', function (Blueprint $table) {
            $table->bigIncrements('idOrv');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idPrv')->unsigned();
            $table->foreign('idPrv')->references('idPrv')->on('proveedor');
            $table->string('orvNumRea');
            $table->string('orvFech')->nullable();
            $table->string('orvUsrG');
            $table->string('orvObs')->nullable();
            $table->integer('orvEst');
            $table->integer('orvEstTrans');
            $table->string('orvNumTrj');
            $table->bigInteger('idTipPag')->unsigned();
            $table->foreign('idTipPag')->references('idTipPag')->on('tipo_pago');  
            $table->bigInteger('idMon')->unsigned();
            $table->foreign('idMon')->references('idMon')->on('moneda');      
            $table->timestamps();
        });

        Schema::create('ord_venta_det', function (Blueprint $table) {
            $table->bigIncrements('idOrvd');
            $table->bigInteger('idOrv')->unsigned();
            $table->foreign('idOrv')->references('idOrv')->on('ord_venta');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->string('orpvPrdCod');
            $table->string('orpvPrdDes');
            $table->integer('orpvCant');
            $table->integer('orpvPrecio');
            $table->string('orpdObs')->nullable();
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
