const info = document.getElementById("info");
const barraLateral = document.querySelector(".barra-lateral");
const spans = document.querySelectorAll("span");
const oscuro = document.querySelector(".switch");
const circulo = document.querySelector(".circulo");
const menu = document.querySelector(".menu");
const main = document.querySelector("main");
//MINIMIZAR SIDEBAR
info.addEventListener("click", () => {
    barraLateral.classList.toggle("mini-barra-lateral");
    main.classList.toggle("min-main");

    spans.forEach((span) => {
        span.classList.toggle("oculto");
    });
})
//MODO OSCURO
oscuro.addEventListener("click", () => {
    let body = document.body;
    body.classList.toggle("dark-mode");
    circulo.classList.toggle("prendido");
})
//MENU
menu.addEventListener("click", () => {
    barraLateral.classList.toggle("max-barra-lateral")
    if (barraLateral.classList.contains("max-barra-lateral")) {
        menu.children[0].style.display = "none";
        menu.children[1].style.display = "block";
    }
    else {
        menu.children[1].style.display = "none";
        menu.children[0].style.display = "block";
    }
    if (window.innerWidth <= 320) {
        barraLateral.classList.add("mini-barra-lateral");
        main.classList.add("min-main");
        spans.forEach((span) => {
            span.classList.add("oculto");
        })
    }
})



//switch led
const switch_led_status = document.getElementById("led-status");
const switch_led = document.querySelector(".switch-led");
const circulo_led = document.querySelector(".circulo-led");
var toggleLed = false;
switch_led_status.addEventListener("click", () => {
    if (!toggleLed) {
        switch_led.classList.toggle("led-off");
        circulo_led.classList.toggle("prendido");
        toggleLed = true;
        switchToggleStatus();
    }
    toggleLed = false;
})

function switchToggleStatus() {
    $.ajax({
        /*url: 'http://bandooriginalespapi.mooo.com/switchToggleStatus.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response);
        },
        error: function (xhr, status, error) {
            // Manejar errores
            console.error(error);
        }*/
    });
}


var currentMarkers = []
crearMarcador();
setInterval(crearMarcador, 5000); function crearMarcador() {
    $.ajax({
        /*url: 'http://bandooriginalespapi.mooo.com/ver_datos_sensor.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response[0]);
            respuesta = response[0];
            const hmd = document.getElementById("humed");
            hmd.innerHTML = "<h2>Temperatura</h2><p>"+respuesta.temperatura+"</p><h2>Humedad</h2><p>"+respuesta.humedad;
        },
        error: function (xhr, status, error) {
            // Manejar errores
            console.error(error);
        }*/
    });
}

var currentMarkers = []
crearMarcador();
setInterval(crearMarcador, 2000);