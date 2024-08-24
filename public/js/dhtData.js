verTempHumed();
setInterval(verTempHumed, 5000); function verTempHumed() {
    $.ajax({
        url: 'https://laravel-esp32.onrender.com/verDatosSensor',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            respuesta = response;
            const hmd = document.getElementById("humed");
            hmd.innerHTML = "<h2>Temperatura</h2><p>"+respuesta.temperatura+"</p><h2>Humedad</h2><p>"+respuesta.humedad;
        },
        error: function (xhr, status, error) {
            // Manejar errores
            console.error(error);
        }
    });
}

var currentMarkers = []
verTempHumed();
setInterval(verTempHumed, 2000);
