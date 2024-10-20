<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="router" method="GET">
        <input type="submit" name="action" value="Home" />
        <input type="submit" name="action" value="Login" />
        <input type="submit" name="action" value="Seleccion" />
    </form>

</body>
</html>

<?php
function home() {
    echo "Hola, Bienvenido";
}

function login() {
    if (!isset($_POST['username']) && !isset($_POST['password'])) {
        require_once 'app/visual/formulario-login.php';
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        echo "Logueado como $username. ¡Registrado exitosamente!";
    }
}

function seleccion() {
    require_once 'app/controllers/seleccion.php';
}

function agregar() {

    echo "<h3> AGREGAR JUGADORES </h3>";

    if (!isset($_POST['jugador-nombre']) && !isset($_POST['categoria'])) {
        include_once 'app/visual/form-agregar.php';
    } else {
        $jugador = $_POST['jugador-nombre'];
        $categoria = $_POST['categoria'];

        $id = agregarJugadores($jugador, $categoria);

        if ($id) { // IF POR DEFECTO MARCA 0 COMO FALSE Y 1,2,3... COMO TRUE
            header("Location: http://localhost/Web-tpe/Seleccion/$categoria");
            exit();
        } else {
            echo "Error al agregar, intente nuevamente.";
        }
    }
}

function eliminar($id) { 
    eliminarjugador($id); // Elimina el jugador
    header("Location: http://localhost/Web-tpe/Seleccion");
    exit();
}

function editar($id, $categoria) {
    $db = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['guardar'])) {
            $nuevoValor = htmlspecialchars(trim($_POST['nuevo_valor']));

            // Actualizar solo la categoría específica en la base de datos
            $query = $db->prepare("UPDATE `posiciones_nombres` SET $categoria = ? WHERE ID = ?");
            $query->execute([$nuevoValor, $id]);

            header("Location: " . BASE_URL . "$categoria");
            exit();
        } elseif (isset($_POST['eliminar'])) {
            eliminarjugador($id); // Eliminar el jugador
            header("Location: " . BASE_URL . "Seleccion");
            exit();
        }
    } else {
        // Obtener los datos actuales para mostrar el ítem específico
        $query = $db->prepare("SELECT $categoria FROM `posiciones_nombres` WHERE ID = ?");
        $query->execute([$id]);
        $jugador = $query->fetch(PDO::FETCH_OBJ);

        if ($jugador) {
            echo '<form action="' . BASE_URL . 'editar/' . $id . '/' . $categoria . '" method="POST">';
            echo '<label for="nuevo_valor">' . ucfirst($categoria) . ':</label>';
            echo '<input type="text" id="nuevo_valor" name="nuevo_valor" value="' . htmlspecialchars($jugador->$categoria) . '" required>';
            echo '<button type="submit" name="guardar">Guardar cambios</button>';
            echo '<button type="submit" name="eliminar" style="background-color: red; color: white;">Eliminar</button>';
            echo '</form>';
        } else {
            echo "Jugador no encontrado en la categoría $categoria.";
        }
    }
}


function Categorias() {
    require_once "app/controllers/AGREGAR_CATEGORIA.php"; // Formulario para el nombre de la columna

    // Conexión 
    $db = new PDO('mysql:host=localhost;dbname=seleccion_argentina;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mostrar Columnas
    echo "<h3>CATEGORÍAS:</h3>";
    $columnas = $db->query("SHOW COLUMNS FROM posiciones_nombres")->fetchAll(PDO::FETCH_OBJ);
    
    // Agregar columna 
    if (!empty($_POST['nombre_columna'])) {
        $nombre_columna = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
        if ($nombre_columna && !$db->query("SHOW COLUMNS FROM posiciones_nombres LIKE '$nombre_columna'")->rowCount()) {
            $db->exec("ALTER TABLE posiciones_nombres ADD COLUMN $nombre_columna TEXT");
            echo "Columna '$nombre_columna' agregada.<br>";
            header("Location: http://localhost/web-tpe/Seleccion"); 
            exit();
        } 
    }

    // Eliminar Columna
    if (!empty($_POST['columna_a_eliminar'])) {
        $columnaAEliminar = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['columna_a_eliminar']);
        if ($columnaAEliminar && $db->query("SHOW COLUMNS FROM posiciones_nombres LIKE '$columnaAEliminar'")->rowCount() > 0) {
            $db->exec("ALTER TABLE posiciones_nombres DROP COLUMN $columnaAEliminar");
            echo "Columna '$columnaAEliminar' eliminada.<br>";
            header("Location: http://localhost/web-tpe/Seleccion"); 
            exit();
        } 
    }

    // Editar Columna
    if (!empty($_POST['columna_a_editar']) && !empty($_POST['nuevo_nombre'])) {
        $columnaAEditar = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['columna_a_editar']);
        $nuevoNombre = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nuevo_nombre']);
        
        // Verificar si el nuevo nombre no existe
        if ($nuevoNombre && !$db->query("SHOW COLUMNS FROM posiciones_nombres LIKE '$nuevoNombre'")->rowCount()) {
            $db->exec("ALTER TABLE posiciones_nombres CHANGE $columnaAEditar $nuevoNombre TEXT");
            echo "Columna '$columnaAEditar' renombrada a '$nuevoNombre'.<br>";
            header("Location: http://localhost/web-tpe/Seleccion"); 
            exit();
        } else {
            echo "Error: Ya existe una columna con el nombre '$nuevoNombre'.<br>";
        }
    }

    // Mostrar columnas (EXISTENTES)
    foreach ($columnas as $columna) {
        echo "<strong>" . htmlspecialchars($columna->Field) . "</strong> ";

        // Formulario para eliminar columna
        echo '<form method="POST" style="display:inline;">
                <input type="hidden" name="columna_a_eliminar" value="' . htmlspecialchars($columna->Field) . '">
                <button type="submit">Eliminar</button>
              </form>';

        // Formulario Editar Columna
        echo '<form method="POST" style="display:inline;">
                <input type="hidden" name="columna_a_editar" value="' . htmlspecialchars($columna->Field) . '">
                <input type="text" name="nuevo_nombre" placeholder="Nuevo nombre" required>
                <button type="submit">Editar</button>
              </form><br>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'login') {
            login(); // Llamada a la función de login
        } elseif ($_POST['action'] === 'agregar_categoria') {
            Categorias(); // Llamada a la función de categorías
        }
    }
}

function MostrarTabla() {
    require_once 'app/models/db.php'; 

    $items = mostrarContenido(); 

    if (count($items) > 0) {
        $columnas = array_keys($items[0]);

        echo "<table border='1'>";
        echo "<thead><tr>";
        echo "<th>ID</th>";
        foreach ($columnas as $columna) {
            echo "<th>" . htmlspecialchars(ucfirst($columna)) . "</th>";
        }
        echo "<th>Acciones</th>";
        echo "</tr></thead>";

        echo "<tbody>";
        foreach ($items as $fila) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['ID']) . "</td>";
            foreach ($columnas as $columna) {
                echo "<td><a href='" . BASE_URL . "editar/" . $fila['ID'] . "/" . $columna . "'>" . htmlspecialchars($fila[$columna]) . "</a></td>";
            }
            echo "<td>
                    <form action='" . BASE_URL . "eliminar/" . $fila['ID'] . "' method='POST' style='display:inline;'>
                        <button type='submit'>Eliminar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No hay datos para mostrar.";
    }

    // Agregar formularios para nuevas categorías o jugadores
    Categorias();
    agregar();
}


function mostrarCategorias() {
    require_once 'app/models/db.php';

    $categorias = obtenerCategorias(); // Obtiene las categorías de la base de datos

    echo "<h2>Categorías</h2>";
    echo "<ul>";
    foreach ($categorias as $categoria) {
        echo '<li><a href="' . BASE_URL . 'Seleccion?categoria=' . $categoria . '">' . ucfirst($categoria) . '</a></li>';
    }
    echo "</ul>";

    // Mostrar jugadores si se ha seleccionado una categoría
    if (isset($_GET['categoria'])) {
        $categoriaSeleccionada = $_GET['categoria'];
        $jugadores = obtenerJugadoresPorCategoria($categoriaSeleccionada); // Obtiene jugadores de la base de datos

        echo "<h3>Jugadores en $categoriaSeleccionada</h3>";
        if (!empty($jugadores)) {
            echo "<ul>";
            foreach ($jugadores as $jugador) {
                echo "<li>" . nl2br(htmlspecialchars($jugador)) . "</li>"; // Usar nl2br para saltos de línea
            }
            echo "</ul>";
        } else {
            echo "<p>No hay jugadores en esta categoría.</p>";
        }
    }
    Categorias(); // Asegúrate de que esta función esté bien definida para mostrar el formulario de agregar
    echo "<br>";
    echo "Agregar Jugador";
    agregar(); // Asegúrate de que esta función esté bien definida para mostrar el formulario de agregar jugador
}
?>
