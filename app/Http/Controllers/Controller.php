<?php

namespace App\Http\Controllers;

abstract class Controller
{
    function calculateSquareVertices($center, $sizeInMeters) {
        // Radio de la Tierra en metros
        $earthRadius = 6371000; // en metros

        // Convertir el tamaño de metros a grados
        $sizeInDegreesLat = $sizeInMeters / $earthRadius * (180 / pi());

        // Calcular el tamaño en grados de longitud basado en la latitud
        $sizeInDegreesLon = $sizeInMeters / ($earthRadius * cos(deg2rad($center['lat']))) * (180 / pi());

        // Calcular los cuatro vértices
        $northEast = [
            'lat' => $center['lat'] + $sizeInDegreesLat,
            'lon' => $center['lon'] + $sizeInDegreesLon
        ];

        $northWest = [
            'lat' => $center['lat'] + $sizeInDegreesLat,
            'lon' => $center['lon'] - $sizeInDegreesLon
        ];

        $southEast = [
            'lat' => $center['lat'] - $sizeInDegreesLat,
            'lon' => $center['lon'] + $sizeInDegreesLon
        ];

        $southWest = [
            'lat' => $center['lat'] - $sizeInDegreesLat,
            'lon' => $center['lon'] - $sizeInDegreesLon
        ];

        return [
            'northEast' => $northEast,
            'northWest' => $northWest,
            'southEast' => $southEast,
            'southWest' => $southWest
        ];
    }
}
