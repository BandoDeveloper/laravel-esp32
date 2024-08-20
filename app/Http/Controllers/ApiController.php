<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    function switchToggleStatus(){
    }
    function verDatosSensor(){
        $sql = DB::select("SELECT * FROM obtener_lectura();");

    }
    function obtenerEstadoLed(){
        $sql = DB::select("SELECT obtener_ultimo_estado();");
        var_dump($sql);
        /*if($sql["estado"] == 1){
            echo "encendido";
        }
        else if($sql["estado"] == 0){
            echo "apagado";
        }
        else{
            echo "error";
        }
        exit();*/
    }
}
