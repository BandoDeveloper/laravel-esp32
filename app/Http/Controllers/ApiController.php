<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    function switchToggleStatus()
    {
        $sql = DB::select("SELECT obtener_ultimo_estado() as estado;");
        $data = get_object_vars($sql[0]);
        if ($data["estado"] == 'true') {
            DB::insert("insert into estado_led values(null, false)");
            return response("apagado", 200);
        } else if ($data["estado"] == 'false') {
            DB::insert("insert into estado_led values(null, true)");
            return response("encendido". 200);
        }
    }
    function verDatosSensor()
    {
        header("");
        $sql = DB::select("SELECT * FROM obtener_lectura();");
        $data = get_object_vars($sql[0]);
        return response()->json($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    function obtenerEstadoLed()
    {
        $sql = DB::select("SELECT obtener_ultimo_estado();");
        $cond = get_object_vars($sql[0])['obtener_ultimo_estado'];
        if ($cond == 'true') {
            return response("encendido", 200);
        } else if ($cond == 'false') {
            return response("apagado", 200);
        } else {
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
