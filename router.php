<?php 
include_once 'app/controllers/Funciones.php';
include_once 'app/controllers/seleccion.php';
include_once 'app/models/db.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

// Verificar si se ha enviado una acción
$action = 'Home';
if (!empty($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
}

$params = explode('/', $action);

switch ($params[0]) {
    case "Home":
        home();
        break;

    case "Login":
        login();
        break;

    case "agregar":
        agregar();
        break;

        case "eliminar":
            if (isset($params[1])) { 
                eliminar($params[1]);
            } else {
                echo "Error: ID no especificado para eliminar.";
            }
            break;
        
        break;

    case "editar":
        if (isset($params[1]) && isset($params[2])) {
            editar($params[1], $params[2]);
        } else {
            echo "Error: ID o categoría no especificados para editar.";
        }
        break;

    case "Seleccion":
        MostrarTabla();
        break;               

    default:
        echo "Acción no válida.";
        break;
}
?>
