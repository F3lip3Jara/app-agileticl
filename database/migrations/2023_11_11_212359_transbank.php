<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vent_tranbank', function (Blueprint $table) {
            $table->bigIncrements('traId');
            $table->bigInteger('orvId')->unsigned();
            $table->foreign('orvId')->references('orvId')->on('vent_orden');     
            $table->bigInteger('empId')->unsigned();
            $table->foreign('empId')->references('empId')->on('parm_empresa');            
            $table->longText('json')->nullable();
            $table->longText('token_ws')->nullable();
            $table->string('transtatus')->nullable();
            $table->string('transtip')->nullable();
            $table->integer('trancentra')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
