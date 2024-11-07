<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /*function switchToggleStatus()
    {
        $sql = DB::select("SELECT obtener_ultimo_estado() as estado;");
        $data = get_object_vars($sql[0]);
        if ($data['estado']) {
            DB::insert("insert into estado_led(estado) values(false)");
            return response()->json("apagado", 200, [
                'Content-Type' => 'application/json'
            ]);
            echo("DATA = STRING");
        } else if (!$data['estado']) {
            DB::insert("insert into estado_led(estado) values(true)");
            return response()->json("encendido", 200, [
                'Content-Type' => 'application/json'
            ]);
            echo("DATA = BOOL");
        }
        return response()->json("ERROR", 500, [
            'Content-Type' => 'application/json'
        ]);
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
        if ($cond) {
            return response("encendido", 200);
        } else if (!$cond) {
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
    }*/
    function A9gLocation()
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
    function phoneLocation(Request $req){
        $data = $req->all();
        $latitud = $limitedString = substr($data['latitud'], 0, 10);
        $longitud = substr($data['longitud'], 0, 10);
        $centerPoint = ['lat' => (float) $latitud, 'lon' => (float) $longitud];
        $earthRadius = 6371000;
        // Convertir el tamaño de metros a grados
        $sizeInDegreesLat = 100 / $earthRadius * (180 / pi());
        // Calcular el tamaño en grados de longitud basado en la latitud
        $sizeInDegreesLon = 100 / ($earthRadius * cos(deg2rad($centerPoint['lat']))) * (180 / pi());
        // Calcular los cuatro vértices
        $northEast = [
            'lat' => $centerPoint['lat'] + $sizeInDegreesLat,
            'lon' => $centerPoint['lon'] + $sizeInDegreesLon
        ];
        $northWest = [
            'lat' => $centerPoint['lat'] + $sizeInDegreesLat,
            'lon' => $centerPoint['lon'] - $sizeInDegreesLon
        ];
        $southEast = [
            'lat' => $centerPoint['lat'] - $sizeInDegreesLat,
            'lon' => $centerPoint['lon'] + $sizeInDegreesLon
        ];
        $southWest = [
            'lat' => $centerPoint['lat'] - $sizeInDegreesLat,
            'lon' => $centerPoint['lon'] - $sizeInDegreesLon
        ];
        $fence = [
            'coordinates' => [
                $northEast,
                $northWest,
                $southWest,
                $southEast,
                $northEast // Repetir el primer punto para cerrar el cuadrado
            ]
        ];
        $jsonFence = json_encode($fence, JSON_PRETTY_PRINT);
    	// Mostrar el resultado
        try {
            // Realiza la inserción
            DB::insert("insert into latlon_telefono(latitud, longitud, geocerca) values(?, ?, ?)", [$latitud, $longitud, $jsonFence]);
            // Respuesta exitosa
            return response()->json(['message' => 'Inserción exitosa.'], 200);
        } catch (QueryException $e) {
            // Manejo de errores
            return response()->json(['error' => 'Error en la inserción: ' . $e->getMessage()], 400);    
        }
    }
    function a9glocationFromsms(Request $req){
        $sql = DB::select("SELECT geocerca from latlon_telefono ORDER BY id DESC LIMIT 1");
        $geocerca = get_object_vars($sql[0]);
        var_dump($geocercaArray[0]);
        $data = $req->all();
        $dataLoc = $data['latlon'];
        $coordinates = explode(",", $dataLoc);
        if (count($coordinates) === 2) {
            $latitude = floatval(trim($coordinates[0]));  // Convertir a float y eliminar espacios
            $longitude = floatval(trim($coordinates[1])); // Convertir a float y eliminar espacios
            echo $latitude;
            echo $longitude;
            try {
                // Realiza la inserción
                DB::insert("insert into latlon_a9g(latitud, longitud, codigo) values(?, ?, ?)", [$latitude, $longitude, 10]);
                // Respuesta exitosa
                //return response()->json(['message' => ''], 200);
            } catch (QueryException $e) {
                // Manejo de errores
                //return response()->json(['error' => 'Error en la inserción: ' . $e->getMessage()], 400);
            }
        } else {
            // Manejar el caso en que no se obtienen dos coordenadas
            echo "Wrong";
        }
    }
    function phoneGeocerca()
    {
        header("");
        $sql = DB::select("SELECT geocerca from latlon_telefono ORDER BY id DESC LIMIT 1");
        $data = get_object_vars($sql[0]);
        return response()->json($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    function A9gLocationDB(Request $req){
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
