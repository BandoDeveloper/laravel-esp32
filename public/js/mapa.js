var currentMarkers = [];
var currentFence; // Variable para almacenar la geocerca

function crearMarcador() {
    $.ajax({
        url: 'https://laravel-esp32.onrender.com/phoneGeocerca',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Manejar la respuesta
            console.log(response);
            // Acceder a los datos
            var latitud = response[0].latitud;
            var longitud = response[0].longitud;
            var codigo = response[0].codigo;
            console.log(latitud, longitud, codigo);

            // Borrar los marcadores anteriores
            if (currentMarkers.length > 0) {
                for (var i = currentMarkers.length - 1; i >= 0; i--) {
                    currentMarkers[i].remove();
                }
            }

            // Crear un nuevo marcador
            const marker1 = new mapboxgl.Marker()
                .setLngLat([longitud, latitud])
                .addTo(map);
            currentMarkers.push(marker1);
            map.setCenter([longitud, latitud]);

            // Llamar a la función para actualizar la geocerca
            crearGeocerca(response);
        },
        error: function(xhr, status, error) {
            // Manejar errores
            console.error(error);
        }
    });
}

function crearGeocerca(response) {
    // Suponiendo que la geocerca viene en la respuesta
    // Aquí debes adaptar la lógica según la estructura de tu respuesta
    var geocerca = response[0].geocerca; // Cambia esto según tu estructura real

    // Borrar la geocerca anterior si existe
    if (currentFence) {
        map.removeLayer('fence-layer');
        map.removeSource('fence');
    }

    // Crear la nueva geocerca
    const coordinates = geocerca.coordinates.map(coord => [coord.longitud, coord.latitud]); // Asegúrate de que los nombres de las propiedades coincidan con tu respuesta
    map.addSource('fence', {
        'type': 'geojson',
        'data': {
            'type': 'Feature',
            'geometry': {
                'type': 'Polygon',
                'coordinates': [coordinates]
            }
        }
    });

    map.addLayer({
        'id': 'fence-layer',
        'type': 'fill',
        'source': 'fence',
        'layout': {},
        'paint': {
            'fill-color': 'rgba(255, 0, 0, 0.5)', // Color rojo con opacidad
            'fill-outline-color': 'rgba(255, 0, 0, 1)' // Color rojo sólido para el borde
        }
    });
}

// Inicializar el primer marcador y la geocerca
crearMarcador();
setInterval(crearMarcador, 5000);
