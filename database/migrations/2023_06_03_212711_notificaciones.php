<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->bigIncrements('idNot');
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');           
            $table->string('notUso');          
            $table->string('notEst');                  
            $table->longText('notObs')->nullable();
            $table->string('notLotSal')->nullable();     
            $table->timestamps();
        });

        Schema::create('notificaciones_visualizaciones', function (Blueprint $table) {
            $table->bigIncrements('idNotv');            
            $table->bigInteger('idNot')->unsigned();
            $table->foreign('idNot')->references('idNot')->on('notificaciones');       
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('empresa');           
            $table->string('notvUso');  
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
