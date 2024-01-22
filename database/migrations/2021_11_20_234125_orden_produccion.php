<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdenProduccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_orden', function (Blueprint $table) {
            $table->bigIncrements('orpId');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');
            $table->bigInteger('prvId')->unsigned();
            $table->foreign('prvId')->references('prvId')->on('parm_proveedor');
            $table->bigInteger('orpidEta')->unsigned();
            $table->string('orpNumOc');
            $table->string('orpNumRea');
            $table->string('orpFech')->nullable();
            $table->string('orpUsrG');
            $table->string('orpObs')->nullable();
            $table->string('orpTurns')->nullable();
            $table->integer('orpEst');
            $table->integer('orpEstPrc');
            $table->timestamps();
        });

        Schema::create('prod_orden_det', function (Blueprint $table) {
            $table->bigIncrements('orpdId');
            $table->bigInteger('orpId')->unsigned();
            $table->foreign('orpId')->references('orpId')->on('prod_orden');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');
            $table->string('orpdPrdCod');
            $table->string('orpdPrdDes');
            $table->integer('orpdCant');
            $table->integer('orpdCantDis');
            $table->integer('orpdTotP');
            $table->bigInteger('orpdetaId')->unsigned()->nullable();
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
