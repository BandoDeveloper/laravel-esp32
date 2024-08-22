function crearMarcador() {
    $.ajax({
        url: 'https://laravel-esp32.onrender.com/LocationFromDB',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Manejar la respuesta
            console.log(response);
            // Acceder a los datos
            var latitud = response.latitud;
            var longitud = response.longitud;
            console.log(latitud, longitud);
            if (currentMarkers!==null) {
                for (var i = currentMarkers.length - 1; i >= 0; i--) {
                    currentMarkers[i].remove();
                }
            }
            const marker1 = new mapboxgl.Marker()
            .setLngLat([longitud, latitud])
            .addTo(map);
            currentMarkers.push(marker1)
            map.setCenter([longitud, latitud]);
        },
        error: function(xhr, status, error) {
            // Manejar errores
            console.error(error);
        }
    });
}

var currentMarkers = []
crearMarcador();
setInterval(crearMarcador, 5000);
