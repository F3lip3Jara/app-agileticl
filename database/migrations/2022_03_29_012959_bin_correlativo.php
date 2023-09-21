<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BinCorrelativo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bins_col', function (Blueprint $table) {
            $table->bigIncrements('idColb');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idEta')->unsigned();
            $table->foreign('idEta')->references('idEta')->on('etapasUser');
            $table->bigInteger('colbnum')->unsigned();
            $table->char('colbtip')->nullable();
            $table->timestamps();
        });

        Schema::create('bins_col_his', function (Blueprint $table) {
            $table->bigIncrements('idColb_h');
            $table->bigInteger('idColb')->unsigned();
            $table->foreign('idColb')->references('idColb')->on('bins_col');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');
            $table->bigInteger('idEta')->unsigned();
            $table->foreign('idEta')->references('idEta')->on('etapasUser');
            $table->bigInteger('colbnum_h')->unsigned();
            $table->char('colbtip_h')->nullable();
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
