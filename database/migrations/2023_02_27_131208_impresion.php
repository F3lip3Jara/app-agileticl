<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Impresion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impresion', function (Blueprint $table) {
            $table->bigIncrements('idImp');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idOrdt')->unsigned();
            $table->foreign('idOrdt')->references('idOrdt')->on('ord_trabajo');
            $table->bigInteger('idOrdtd')->unsigned();
            $table->foreign('idOrdtd')->references('idOrdtd')->on('ord_trabajo_det');
            $table->string('impUso');          
            $table->string('impEst');
            $table->string('impEstCtl');
            $table->string('impMaq')->nullable();
            $table->string('impTip')->nullable(); 
            $table->string('impPrdCaja')->nullable();
            $table->string('impPrdBolsa')->nullable();  
            $table->string('impLotCaja')->nullable();
            $table->string('impLotBolsa')->nullable();           
            $table->longText('impObs')->nullable();
            $table->string('impLotSal')->nullable();
            $table->integer('impTurn')->nullable();
            $table->integer('impReproceso')->nullable();
            $table->integer('impBasura')->nullable();
            $table->integer('impMerma')->nullable();
            $table->integer('impidTer')->nullable();            
            $table->timestamps();
        });

        Schema::create('impresion_det', function (Blueprint $table) {
            $table->bigIncrements('idImpd');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idImp')->unsigned();
            $table->foreign('idImp')->references('idImp')->on('impresion');
            $table->string('impdHorIni');
            $table->string('impdHorFin')->nullable();
            $table->integer('impdPesoCaja')->nullable(); 
            $table->integer('impdCajaAcu')->nullable(); 
            $table->bigInteger('impdidMot')->nullable();
            $table->string('impdDefecto')->nullable();
            $table->string('impdUso');
            $table->string('impdRol');            
            $table->string('impdTip');
            $table->string('impdEst');
            $table->timestamps();
        });


        Schema::create('impresion_det_peso', function (Blueprint $table) {
            $table->bigIncrements('idImpp');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idImp')->unsigned();
            $table->foreign('idImp')->references('idImp')->on('impresion');  
            $table->string('imppUso');
            $table->string('imppRol');            
            $table->string('imppTip');  
            $table->decimal('impPeso' , 10 , 2);                    
            $table->timestamps();
        });

        Schema::create('impresion_tinta', function (Blueprint $table) {
            $table->bigIncrements('idImpt');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idImp')->unsigned();
            $table->foreign('idImp')->references('idImp')->on('impresion');                       
            $table->string('imptPrd')->nullable();
            $table->string('imptPrdLote');
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
