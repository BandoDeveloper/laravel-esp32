var currentMarkers = [];
var currentFence; // Variable para almacenar la geocerca principal
var Draw; // Variable para la herramienta de dibujo
var centered = false;
var geocercasSecundarias = []; // Arreglo para almacenar geocercas secundarias

// Inicializar el mapa
mapboxgl.accessToken = 'pk.eyJ1IjoiZXN0ZWJhbi1tZWRyYW5vIiwiYSI6ImNsdWx3a3ZyYzE3ZW4ya3A1aWpxNG13bWgifQ.-9jmRfnuAAThDXtwiW54Jg';
const map = new mapboxgl.Map({
    container: 'map',
    center: [-68.11942, -16.503327], // Ajusta la posición inicial al punto proporcionado
    zoom: 15 // Ajusta el zoom inicial
});

// Inicializar la herramienta de dibujo
Draw = new MapboxDraw({
    displayControlsDefault: false,
    controls: {
        polygon: true,
        trash: true
    }
});
map.addControl(Draw);

// Definición de las geocercas en formato GeoJSON
var geocercasGeoJSON = '{"type":"FeatureCollection","features":[{"id":"geocerca1","type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[-68.12497971964143,-16.494828783456185],[-68.11949542298854,-16.49154481441566],[-68.11729894944011,-16.49450971913116],[-68.12085039902082,-16.496305042903302],[-68.12497971964143,-16.494828783456185]]]}},{"id":"geocerca2","type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[-68.14466272272884,-16.506145296975305],[-68.14090598821001,-16.50027405022709],[-68.1462245175756,-16.498304566809026],[-68.14995887746498,-16.504726565135712],[-68.14466272272884,-16.506145296975305]]]}},{"id":"6ba81d2d44aabfeea6ad6a6d64001d59","type":"Feature","properties":{},"geometry":{"coordinates":[[[-68.13643028783648,-16.496110880477914],[-68.13204681301171,-16.49945214935542],[-68.12631223003432,-16.496434208459448],[-68.13395499059871,-16.49164152019287],[-68.13643028783648,-16.496110880477914]]],"type":"Polygon"}}]}';

function agregarGeocercas(geojson) {
    const data = JSON.parse(geojson);
    data.features.forEach(feature => {
        // Agregar la fuente para la geocerca
        map.addSource(feature.id, {
            'type': 'geojson',
            'data': feature
        });

        // Agregar la capa para la geocerca
        const layerId = feature.id + '-layer';
        map.addLayer({
            'id': layerId,
            'type': 'fill',
            'source': feature.id,
            'layout': {},
            'paint': {
                'fill-color': 'rgba(100, 255, 0, 0.4)',
                'fill-outline-color': 'rgba(0, 0, 0, 1)'
            },
        });

        // Agregar el evento de clic para la capa de la geocerca
        map.on('click', layerId, function(e) {
            eliminarGeocercaSecundaria(feature.id); // Llama a la función de eliminación
        });

        // Agregar a geocercas secundarias
        geocercasSecundarias.push(feature);
    });
}

// Llamar a la función para agregar las geocercas al mapa después de que se haya cargado
map.on('load', function() {
    console.log(geocercasGeoJSON)
    $.ajax({
        url: 'https://laravel-esp32.onrender.com/getSafeZones',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            agregarGeocercas(data);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
    crearMarcador(); // Llamar a crearMarcador después de que el mapa se haya cargado
    setInterval(crearMarcador, 5000);
});

function crearMarcador() {
    $.ajax({
        url: 'https://laravel-esp32.onrender.com/phoneGeocerca',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var latitud = response[0].latitud;
            var longitud = response[0].longitud;

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
            if (!centered) {
                map.setCenter([longitud, latitud]);
                centered = true;
            }

            // Llamar a la función para actualizar la geocerca principal
            crearGeocercaPrincipal(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function crearGeocercaPrincipal(response) {
    var geocerca = response[0].geocerca; // Suponiendo que la geocerca principal viene en la respuesta

    // Borrar la geocerca anterior si existe
    if (currentFence) {
        if (map.getLayer('fence-layer')) {
            map.removeLayer('fence-layer');
        }
        if (map.getSource('fence')) {
            map.removeSource('fence');
        }
    }

    // Crear la nueva geocerca principal
    const coordinates = geocerca.coordinates.map(coord => [coord.longitud, coord.latitud]);
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

    currentFence = true;
}

// Función para eliminar geocercas secundarias
function eliminarGeocercaSecundaria(id) {
    if (map.getLayer(id + '-layer')) {
        map.removeLayer(id + '-layer');
    }
    if (map.getSource(id)) {
        map.removeSource(id);
    }
    geocercasSecundarias = geocercasSecundarias.filter(feature => feature.id !== id);
}



//funciones de dibujo
// Arreglo para almacenar geocercas dibujadas
var geocercasDibujadas = [];

// Escuchar eventos de creación de dibujos
map.on('draw.create', function(event) {
    const features = event.features;
    // Almacenar las geocercas dibujadas
    geocercasDibujadas = geocercasDibujadas.concat(features); // O usar: geocercasDibujadas.push(...features);
});

// Escuchar eventos de eliminación de dibujos
map.on('draw.delete', function(event) {
    const deletedFeatures = event.features;
    geocercasDibujadas = geocercasDibujadas.filter(function(feature) {
        return !deletedFeatures.some(deleted => deleted.id === feature.id);
    });
});
// Botón para guardar geocercas
const guardarButton = document.getElementById('guardar-geocerca');
guardarButton.onclick = function() {
    const geocercasRestantes = {
        type: "FeatureCollection",
        features: [...geocercasSecundarias, ...geocercasDibujadas] // Combinar geocercas
    };
    console.log(JSON.stringify(geocercasRestantes))
    $.ajax({
        url: 'https://laravel-esp32.onrender.com/safeZoneSave?safeGeoFence='+JSON.stringify(geocercasRestantes),
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    })
};
