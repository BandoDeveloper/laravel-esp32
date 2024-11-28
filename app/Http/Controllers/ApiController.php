<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
class ApiController extends Controller
{
    public function puntosEnGeocerca() {
        // Obtener las coordenadas de la base de datos
        $sql = DB::select("SELECT safezonescoords from zonasseguras ORDER BY id DESC LIMIT 1");
        $latLonArrayBrute = get_object_vars(json_decode(get_object_vars($sql[0])["safezonescoords"]))["geocercas"];

        // Inicializar un array para almacenar las geocercas
        $geocercas = [];

        foreach ($latLonArrayBrute as $index => $coordinates) {
            $coords = get_object_vars($coordinates);
            $geocercaCoords = []; // Array para almacenar las coordenadas de la geocerca actual

            foreach ($coords as $latlon) {
                if (is_object($latlon)) {
                    $latlonDecode = get_object_vars($latlon);
                    // Agregar las coordenadas al array de la geocerca actual
                    $geocercaCoords[] = [
                        'lat' => (float)$latlonDecode["lat"],
                        'lon' => (float)$latlonDecode["lon"]
                    ];
                }
            }

            // Almacenar las coordenadas de la geocerca en el array principal
            $geocercas["geocerca_" . ($index + 1)] = $geocercaCoords; // Usa un identificador único para cada geocerca
        }

        // Ahora $geocercas contiene todas las coordenadas organizadas por geocerca
        return($geocercas);
    }
    private function puntoDentroGeocerca(array $punto, array $poligono) {
        $numVertices = count($poligono);
        $inside = false;

        // Algoritmo de ray-casting para determinar si el punto está dentro del polígono
        for ($i = 0, $j = $numVertices - 1; $i < $numVertices; $j = $i++) {
            $xi = $poligono[$i][0];
            $yi = $poligono[$i][1];
            $xj = $poligono[$j][0];
            $yj = $poligono[$j][1];

            $intersect = (($yi > $punto[1]) != ($yj > $punto[1])) &&
                         ($punto[0] < ($xj - $xi) * ($punto[1] - $yi) / ($yj - $yi) + $xi);
            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }
    public function verificarPuntoEnGeocerca(float $lat, float $lon) {
        // Llama a la función que obtiene las coordenadas
        $geocercas = $this->puntosEnGeocerca(); // Asegúrate de que esta función devuelva el array

        // Recorre cada geocerca
        foreach ($geocercas as $nombreGeocerca => $coordenadas) {
            // Convierte las coordenadas a un formato que pueda ser utilizado por la función de verificación
            $poligono = array_map(function($coord) {
                return [(float)$coord['lon'], (float)$coord['lat']]; // Cambiar el orden a [lon, lat]
            }, $coordenadas);

            // Verifica si el punto está dentro de la geocerca
            if ($this->puntoDentroGeocerca([$lon, $lat], $poligono)) {
                return true;
            }
        }
        return false;
    }
    // Método para probar la funcionalidad
    function pruebaVerificacion() {
        // Define un punto de prueba (latitud, longitud)
        $lat = -16.4948;
        $lon = -68.1220;
        // Llama al método de verificación
        if($this->verificarPuntoEnGeocerca($lat, $lon)){
            return response()->json("Esta en la geocerca", 200);
        }
        else{
            return response()->json("No esta en la geocerca", 200);
        }
    }
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
        $json_geo = $geocerca['geocerca'];
        $fence = json_decode($json_geo, true);
        $firstPoint = $fence['coordinates'][0]; // Coordenadas del norte este
        $lastPoint = $fence['coordinates'][2]; // Coordenadas del sur este
        $data = $req->all();
        $dataLoc = $data['latlon'];
        $coordinates = explode(",", $dataLoc);
        if (count($coordinates) === 2) {
            $latitude = floatval(trim($coordinates[0]));  // Convertir a float y eliminar espacios
            $longitude = floatval(trim($coordinates[1])); // Convertir a float y eliminar espacios
            try {
                // Realiza la inserción
                DB::insert("insert into latlon_a9g(latitud, longitud, codigo) values(?, ?, ?)", [$latitude, $longitude, 10]);
                if($latitude <= $firstPoint['lat'] && $longitude <= $firstPoint['lon'] && $latitude >= $lastPoint['lat'] && $longitude >= $lastPoint['lon']){
                    return response()->json(['message' => 'Dentro'], 200);
                }
                else{
                    if($this->verificarPuntoEnGeocerca($latitude, $longitude)){
                        return response()->json(['message' => 'Safe Zone'], 200);
                    }
                    else{
                        return response()->json(['message' => 'Fuera'], 200);
                    }
                }
            } catch (QueryException $e) {
                // Manejo de errores
                return response()->json(['error' => 'Error en la inserción: ' . $e->getMessage()], 400);
            }
        } else {
            // Manejar el caso en que no se obtienen dos coordenadas
            return response()->json(['error' => 'Error en la inserción: No enviaste los datos'], 400);
        }
    }
    function phoneGeocerca()
    {
        header("");
        $sql = DB::select("SELECT geocerca from latlon_telefono ORDER BY id DESC LIMIT 1");
        $geocerca = get_object_vars($sql[0]);
        $json_geo = $geocerca['geocerca'];
        $fence = json_decode($json_geo, true);
        $first = $fence['coordinates'][0]; // Coordenadas del norte este
        $second = $fence['coordinates'][1]; // Coordenadas del sur este
        $third = $fence['coordinates'][2]; // Coordenadas del norte este
        $last = $fence['coordinates'][3]; // Coordenadas del sur este
        $sql = DB::select("SELECT latitud, longitud, codigo from latlon_a9g ORDER BY id DESC LIMIT 1");
        $locationData = get_object_vars($sql[0]);
        $latitud = $locationData['latitud'];
        $longitud = $locationData['longitud'];
        $codigo = $locationData['codigo'];
        $dataForJS = [
            [
                "latitud" => $latitud,
                "longitud" => $longitud,
                "codigo" => $codigo,
                "geocerca" => [
                    "coordinates" => [
                        ["latitud" => $first['lat'], "longitud" => $first['lon']],
                        ["latitud" => $second['lat'], "longitud" => $second['lon']],
                        ["latitud" => $third['lat'], "longitud" => $third['lon']],
                        ["latitud" => $last['lat'], "longitud" => $last['lon']],
                        ["latitud" => $first['lat'], "longitud" => $first['lon']] // Debe cerrarse el polígono
                    ]
                ]
            ]
        ];
        // Para convertirlo a JSON, usarías:
        $data = json_encode($dataForJS);
        // Mostrar el resultado JSON (opcional)
        echo $data;
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
    function safeZoneSave(Request $req){
        if(isset($req['safeGeoFence'])){
            $safeZones = $req['safeGeoFence'];
            $geocercas = [];
            $geocercasJson = json_decode($safeZones);
            $geocercasDecode = get_object_vars($geocercasJson)['features'];
            $indice = 0;
            foreach ($geocercasDecode as $key) {
                $geocercas[] = ['coordinates' => []];
                $decGeoFence = get_object_vars($key);
                $coords = get_object_vars($decGeoFence['geometry']);
                foreach ($coords['coordinates'][0] as $latLon) {
                    $geocercas[$indice][] = [
                        'lon' => (float)$latLon[0],
                        'lat' => (float)$latLon[1],
                    ];
                }

                $indice++;
            }
            $resultado = [
                'geocercas' => $geocercas
            ];
            // Imprimir el resultado para verificar
            $safeZonesCoded = json_encode($resultado, );
            try{
                DB::insert("insert into zonasSeguras(safeZones, safeZonesCoords) values('$safeZones', '$safeZonesCoded')");
                return response()->json("Insercion Exitosa", 200);
            }catch(QueryException $e){
                return response()->json("Error en la base de datos", status: 500);
            }
        }
        else{
            return response()->json("No hay Datos", status: 400);
        }
    }
    function getSafeZones(){
        try {
            $sql = DB::select("SELECT safezones from zonasseguras ORDER BY id DESC LIMIT 1");
            $data = get_object_vars($sql[0])['safezones'];
            return response()->json($data, 200);
        } catch (QueryException $e) {
            return response()->json("Error en la base de datos", status: 500);
        }
    }
}
