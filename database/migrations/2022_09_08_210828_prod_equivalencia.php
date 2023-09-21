<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProdEquivalencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_equivalencia', function (Blueprint $table) {
            $table->bigIncrements('idEqui');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idPrd')->unsigned();
            $table->foreign('idPrd')->references('idPrd')->on('producto');
            $table->integer('equiPrdBulto')->nullable();
            $table->integer('equiBultPallet')->nullable();
            $table->integer('equiPrdBins')->nullable();
            
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
