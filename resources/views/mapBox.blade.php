<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MapBox</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">
    <link href="https://laravel-esp32.onrender.com/js/sidebar.css" rel="stylesheet">
    <link href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.2.0/mapbox-gl-draw.css' rel='stylesheet' />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.2.0/mapbox-gl-draw.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }

        .info-card {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1;
            width: 300px; /* Aumentar ancho de la tarjeta */
        }

        .card-body p {
            font-size: 1.1rem; /* Aumentar tamaño del texto */
        }

        #guardar-geocerca {
            margin-top: 20px; /* Margen superior para el botón */
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Códigos de Colores</h5>
            <p><span class="badge badge-danger">Rojo</span> Tu posición</p>
            <p><span class="badge badge-success">Verde</span> Geocercas Antiguas</p>
            <p><span class="badge badge-primary">Azul</span> Geocercas Nuevas</p>
            <p class="text-muted">Nota: Presiona una geocerca verde para eliminarla.</p>
        </div>
    </div>
    <button id="guardar-geocerca" class="btn btn-primary" style="position: absolute; top: 5sp; left: 10px; z-index: 1;">Guardar Geocercas</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- cambiar a  ruta de render al hacer deploy -->
    <script src="https://laravel-esp32.onrender.com/js/mapa.js"></script>
</body>

</html>
