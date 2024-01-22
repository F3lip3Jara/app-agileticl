<?php

namespace App\Http\Controllers;

use Exception;
//use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Illuminate\Http\Request;

class Etiquetas extends Controller
{



    public function imprimir(){
        try {

        } catch(Exception $e) {
            return "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }


}
