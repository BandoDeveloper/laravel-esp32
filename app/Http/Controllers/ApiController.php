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
        $cond = get_object_vars($sql[0])['obtener_ultimo_estado'];
        if ($cond == 'true') {
            return response("encendido", 200);
        }
        else if($cond == 'false'){
            return response("apagado", 200);
        }
        else{
            return response("Error", 404);
        }
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
