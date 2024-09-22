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
        echo($data['estado']);
        /*if ($data["estado"] == 'true') {
            DB::insert("insert into estado_led(estado) values(false)");
            return response()->json("apagado", 200, [
                'Content-Type' => 'application/json'
            ]);
        } else if ($data["estado"] == 'false') {
            DB::insert("insert into estado_led(estado) values(true)");
            return response()->json("encendido", 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        return response()->json("ERROR", 500, [
            'Content-Type' => 'application/json'
        ]);*/
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
    }
    function sendDHTData(Request $req)
    {
        if (isset($req['temp'])) {
            $temp = $req['temp'];
            if (isset($req['humed'])) {
                $humed = $req['humed'];
                # code...
                DB::insert("insert into DHT11(temperatura, humedad) values('$temp', '$humed')");
                return response("Datos insertados", 200);
            }
        } else {
            return response("No Data Sent", 400);
        }
    }
    function EspLocation()
    {
        return view("mapBox", []);
    }
    function LocationFromDB()
    {
        $data = DB::select('SELECT latitud, longitud, fechahora, codigo FROM latlon ORDER BY id DESC LIMIT 1');

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Custom-Header' => 'Value' // Example of a custom header
        ]);
    }
    function sendESPLocation(Request $req){
        if (isset($req['lat'])) {
            $lat = $req['lat'];
            if (isset($req['long'])) {
                $long = $req['long'];
                if (isset($req['code'])) {
                    $code = $req['code'];
                    DB::insert("insert into latlon(latitud, longitud, codigo) values('$lat', '$long', '$code')");
                    return response("Datos insertados", 200);
                }
            }
        } else {
            return response("No Data Sent", 400);
        }
    }
}
