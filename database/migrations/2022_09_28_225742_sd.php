<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sd_centro', function (Blueprint $table) {
            $table->bigIncrements('centroId');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');
            $table->string('cenDes');
            $table->string('cenDir');
            $table->longText('cenPlace')->nullable();
            $table->integer('cenCap')->nullable();;
            $table->timestamps();
        });

        Schema::create('sd_centro_alm', function (Blueprint $table) {
            $table->bigIncrements('almId');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');
            $table->bigInteger('centroId')->unsigned();
            $table->foreign('centroId')->references('centroId')->on('sd_centro');
            $table->string('almDes');
            $table->char('almTip');
            $table->integer('almCap');
            $table->timestamps();
        });



        Schema::create('sd_sector', function (Blueprint $table) {
            $table->bigIncrements('sectorId');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa'); 
            $table->bigInteger('centroId')->unsigned();
            $table->foreign('centroId')->references('centroId')->on('sd_centro');  
            $table->bigInteger('almId')->unsigned();
            $table->foreign('almId')->references('almId')->on('sd_centro_alm');           
            $table->string('secDes');
            $table->string('secCod');
            $table->timestamps();
        });

        


    /*    Schema::create('sd_stock', function (Blueprint $table) {
            $table->bigIncrements('idExi');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idCentro')->unsigned();
            $table->bigInteger('idSector')->unsigned();
            $table->bigInteger('idPrd')->unsigned();
            $table->foreign('idPrd')->references('idPrd')->on('producto');
            $table->string('exiDis');
            $table->integer('exiTrans');
            $table->string('exiReser');
            $table->timestamps();
        });*/


         //PALLET
     /*    Schema::create('sd_undida_almacenaje', function (Blueprint $table) {
            $table->bigIncrements('idUnAlm');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->char('unTip')->nullable();
            $table->string('unCod')->nullable();         
            $table->timestamps();
        });


        //PALLET - BULTOS
        Schema::create('undida_almacenaje_bultos', function (Blueprint $table) {
            $table->bigIncrements('idBulAlm');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');         
            $table->bigInteger('idUnAlm')->unsigned();
            $table->foreign('idUnAlm')->references('idUnAlm')->on('undida_almacenaje');         
            $table->integer('bulCant')->nullable();
            $table->string('bulCod')->nullable();
            $table->timestamps();
        });

        Schema::create('undida_almacenaje_bultos_prd', function (Blueprint $table) {
            $table->bigIncrements('idBulPrd');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');         
            $table->bigInteger('idUnAlm')->unsigned();
            $table->foreign('idUnAlm')->references('idUnAlm')->on('undida_almacenaje'); 
            $table->bigInteger('idBulAlm')->unsigned();
            $table->foreign('idBulAlm')->references('idBulAlm')->on('undida_almacenaje_bultos');
            $table->bigInteger('idPrd')->unsigned();
            $table->foreign('idPrd')->references('idPrd')->on('producto');           
            $table->integer('bulPrCant')->nullable();
            $table->timestamps();
        });*/

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
