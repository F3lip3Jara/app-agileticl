<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Envasado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envasado', function (Blueprint $table) {
            $table->bigIncrements('idEnv');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idTer')->unsigned();
            $table->foreign('idTer')->references('idTer')->on('termoformado');
            $table->integer('envTurn'); 
            $table->string('envLotSal');
            $table->string('envPrdCaja')->nullable();
            $table->string('envPrdBolsa')->nullable();  
            $table->string('envLotCaja')->nullable();
            $table->string('envLotBolsa')->nullable(); 
            $table->string('envMaq'); 
            $table->char('envEst'); 
            $table->char('envEstCtl'); 
            $table->longText('envObs')->nullable(); 
            $table->timestamps();
        });

        Schema::create('envasado_det', function (Blueprint $table) {
            $table->bigIncrements('idEnvd');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idEnv')->unsigned();
            $table->foreign('idEnv')->references('idEnv')->on('envasado');
            $table->string('envdHorIni');
            $table->string('envdHorFin')->nullable();
            $table->integer('envdCaja')->nullable(); 
            $table->integer('envdPallet')->nullable(); 
            $table->timestamps();
        });

        Schema::create('envasado_arch', function (Blueprint $table) {
            $table->bigIncrements('idEnvarch');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idEnv')->unsigned();
            $table->foreign('idEnv')->references('idEnv')->on('envasado');           
            $table->string('envLink');        
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
