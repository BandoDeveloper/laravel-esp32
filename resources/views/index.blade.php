<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta</title>
    <link href="https://laravel-esp32.onrender.com/js/sidebar.css" rel="stylesheet">
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
            <button class="boton">
                <ion-icon name="add-circle-outline"></ion-icon>
                <span>Create new</span>
            </button>
        </div>
        <nav class="navegacion">
            <ul>
                <li>
                    <a href="#">
                        <ion-icon name="cube"></ion-icon>
                        <span>Ver mis ventas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="people"></ion-icon>
                        <span>Cerrar Sesion</span>
                    </a>
                </li>
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
        <h1>PANEL DE CONTROL</h1>
        <h2>ESTADO DEL LED</h2>
        <div class="switch-led">
            <div class="modo-oscuro">
                <div class="switch" id="led-status">
                    <div class="base">
                        <div class="circulo-led">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div id="temp"></div>
            <div id="humed"></div>
        </div>
    </main>
</body>
<script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule="" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
</script>
<script src="https://laravel-esp32.onrender.com/js/sidebar.js"></script>
</script>

</html>