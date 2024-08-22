<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MapBox</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">
    <link href="https://laravel-esp32.onrender.com/js/sidebar.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
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
    </style>
</head>

<body>
    <div class="menu">
        <ion-icon name="menu"></ion-icon>
        <ion-icon name="close"></ion-icon>
    </div>
    <div class="barra-lateral">
        <div>
            <div class="nombre-pagina">
                <ion-icon name="information-circle-outline" id="info"></ion-icon>
                <span>Opciones</span>
            </div>
        </div>
        <nav class="navegacion">
            <ul>
                <li>
                    <a href="/">
                        <ion-icon name="cube"></ion-icon>
                        <span>Inicio</span>
                    </a>
                </li>
                <!--<li>
                    <a href="#">
                        <ion-icon name="people"></ion-icon>
                        <span>ADMIN</span>
                    </a>
                </li>-->
            </ul>
        </nav>
        <div>
            <div class="linea"></div>

            <div class="modo-oscuro">
                <div class="info">
                    <ion-icon name="moon"></ion-icon>
                    <span>Osucro</span>
                </div>
                <div class="switch">
                    <div class="base">
                        <div class="circulo">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <main>
        <div id="map"></div>
    </main>
    <script>
        mapboxgl.accessToken =
            'pk.eyJ1IjoiZXN0ZWJhbi1tZWRyYW5vIiwiYSI6ImNsdWx3a3ZyYzE3ZW4ya3A1aWpxNG13bWgifQ.-9jmRfnuAAThDXtwiW54Jg';
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            center: [-74.5, 40], // starting position [lng, lat]
            zoom: 20 // starting zoom
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://laravel-esp32.onrender.com/js/sidebar.js"></script>
    <script src="https://laravel-esp32.onrender.com/js/mapa.js"></script>

</body>

</html>
