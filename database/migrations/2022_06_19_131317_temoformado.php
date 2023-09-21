<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Temoformado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('termoformado', function (Blueprint $table) {
            $table->bigIncrements('idTer');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idOrdt')->unsigned();
            $table->foreign('idOrdt')->references('idOrdt')->on('ord_trabajo');
            $table->bigInteger('idOrdtd')->unsigned();
            $table->foreign('idOrdtd')->references('idOrdtd')->on('ord_trabajo_det');
            $table->string('terUso');          
            $table->string('terEst');
            $table->string('terEstCtl');
            $table->string('terMaq')->nullable();
            $table->string('terTip')->nullable();
            $table->string('terPrdCaja')->nullable();
            $table->string('terPrdBolsa')->nullable();  
            $table->string('terLotCaja')->nullable();
            $table->string('terLotBolsa')->nullable();                  
            $table->longText('terObs')->nullable();
            $table->string('terLotSal')->nullable();
            $table->integer('terTurn')->nullable();
            $table->integer('terCavTot')->nullable();
            $table->integer('terCavAct')->nullable();
            $table->integer('terRepro')->nullable();
            $table->integer('terMerma')->nullable();                   
            $table->timestamps();
        });

        Schema::create('termoformado_det', function (Blueprint $table) {
            $table->bigIncrements('idTerd');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idTer')->unsigned();
            $table->foreign('idTer')->references('idTer')->on('termoformado');           
            $table->string('terdEst');
            $table->string('terdHorIni');
            $table->string('terdHorFin')->nullable();
            $table->string('terdUso');
            $table->string('terdRol');            
            $table->string('terdLotExt');         
            $table->string('terdRechazo')->nullable();
            $table->string('terdLimp')->nullable();
            $table->integer('terdTem')->nullable(); 
            $table->integer('terdCaja')->nullable(); 
            $table->string('terdTipo')->nullable(); 
            $table->bigInteger('terdidMot')->nullable();
            $table->string('terdDefecto')->nullable();
            $table->char('terdSani')->nullable(); 
            $table->char('terdPesoUn')->nullable(); 
            $table->integer('terdUnAlm')->nullable();
            $table->string('terdFechVen')->nullable();  
           // $table->string('terRechazo')->nullable(); 
           // $table->string('terLimpieza')->nullable();  
           //$table->string('terdtipDe')->nullable();
            $table->timestamps();
        });

        Schema::create('termoformado_det_peso', function (Blueprint $table) {
            $table->bigIncrements('idTerp');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idTer')->unsigned();
            $table->foreign('idTer')->references('idTer')->on('termoformado');  
            $table->string('terpUso');
            $table->string('terpRol');            
            $table->string('terptip');  
            $table->decimal('terpPeso' , 10 , 2);                    
            $table->timestamps();
        });

        Schema::create('termoformado_arch', function (Blueprint $table) {
            $table->bigIncrements('idTerarch');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idTer')->unsigned();
            $table->foreign('idTer')->references('idTer')->on('termoformado');           
            $table->string('terarlink');        
            $table->timestamps();
        });

        Schema::create('termoformado_pallet', function (Blueprint $table) {
            $table->bigIncrements('idTerpa');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idTer')->unsigned();
            $table->foreign('idTer')->references('idTer')->on('termoformado');
            $table->bigInteger('idTerd')->unsigned();
            $table->foreign('idTerd')->references('idTerd')->on('termoformado_det');                 
            $table->integer('terpaCor');        
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
       
    }
}
