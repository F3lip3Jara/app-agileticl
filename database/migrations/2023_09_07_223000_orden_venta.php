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
       Schema::create('parm_tipo_pago', function (Blueprint $table) {
            $table->bigIncrements('tipPagId');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');        
            $table->string('tipCod');
            $table->string('tipDes');
            $table->timestamps();
        });

        Schema::create('vent_orden', function (Blueprint $table) {
            $table->bigIncrements('orvId');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');
            $table->bigInteger('prvId')->unsigned();
            $table->foreign('prvId')->references('prvId')->on('parm_proveedor');
            $table->string('orvNumRea')->nullable();
            $table->string('orvFech')->nullable();
            $table->string('orvUsrG')->nullable();
            $table->string('orvObs')->nullable();
            $table->string('orvEst')->nullable();
            $table->string('orvEstTrans')->nullable();
            $table->string('orvNumTrj')->nullable();  
            $table->integer('orvCuota');        
            $table->integer('orvPrecioTot');  
            $table->integer('orvPrecioIva');   
            $table->integer('orvPrecioPag');                 
            $table->bigInteger('tipPagId')->unsigned();
            $table->foreign('tipPagId')->references('tipPagId')->on('parm_tipo_pago');             
            $table->timestamps();
        });

        Schema::create('vent_orden_det', function (Blueprint $table) {
            $table->bigIncrements('orvdId');
            $table->bigInteger('orvId')->unsigned();
            $table->foreign('orvId')->references('orvId')->on('vent_orden');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');
            $table->bigInteger('monId')->unsigned();
            $table->foreign('monId')->references('monId')->on('parm_moneda'); 
            $table->string('orpvPrdCod');
            $table->string('orpvPrdDes');
            $table->double('orpvPrdCost');
            $table->double('orpvPrdNet');
            $table->integer('orpvCant');
            $table->double('orpvPrecio');
            $table->double('orpvDesc')->nullable();
            $table->string('orpvObs')->nullable();          
            $table->timestamps();
        });

        Schema::create('ord_venta_pag', function (Blueprint $table) {
            $table->bigIncrements('orvCuota');
            $table->bigInteger('orvId')->unsigned();
            $table->foreign('orvId')->references('orvId')->on('vent_orden');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');     
            $table->double('orvcCost');
            $table->double('orvcNet');    
            $table->double('orvcPrecio');
            $table->double('orvcPag');
            $table->double('orvcDesc')->nullable();
            $table->string('orvcObs')->nullable();
            $table->longText('orpvtoken')->nullable();
            $table->string('orvcNumRea')->nullable();
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
