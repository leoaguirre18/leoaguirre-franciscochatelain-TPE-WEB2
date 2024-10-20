<?php 

function getConnection() {
    try {
        return new PDO('mysql:host=localhost;dbname=seleccion_argentina;charset=utf8', 'root', '');
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        exit;
    }
}

function agregarJugadores($jugador, $categoria) {
    $db = getConnection();
    
    // Validar que el jugador no contenga caracteres no deseados
    $jugador = preg_replace('/[^a-zA-Z0-9_ ]/', '', $jugador);
    
    $query = $db->prepare("INSERT INTO posiciones_nombres ($categoria) VALUES (?)");
    $query->execute([$jugador]);

    return $db->lastInsertId();
}

function eliminarCampo($id, $columna) {
    $db = getConnection();
    
    // Validar el nombre de la columna
    $columna = preg_replace('/[^a-zA-Z0-9_]/', '', $columna);
    
    $query = $db->prepare("UPDATE posiciones_nombres SET $columna = NULL WHERE ID = ?");
    $query->execute([$id]);
}

function eliminarjugador($id) {
    $db = getConnection();
    
    $query = $db->prepare('DELETE FROM posiciones_nombres WHERE ID = ?');
    $query->execute([$id]);
}

function editarjugador($id, $nuevoNombre, $nuevaCategoria) {
    $db = getConnection();
    
    // Validar nuevo nombre y categoría
    $nuevoNombre = preg_replace('/[^a-zA-Z0-9_ ]/', '', $nuevoNombre);
    $nuevaCategoria = preg_replace('/[^a-zA-Z0-9_]/', '', $nuevaCategoria);
    
    $query = $db->prepare('UPDATE posiciones_nombres SET ' . $nuevaCategoria . ' = ? WHERE ID = ?');
    $query->execute([$nuevoNombre, $id]);
}

function obtenerCategorias() {
    $db = getConnection();
    $query = $db->query("SHOW COLUMNS FROM posiciones_nombres");
    $categorias = [];
    
    while ($columna = $query->fetch(PDO::FETCH_ASSOC)) {
        if ($columna['Field'] != 'ID') {
            $categorias[] = $columna['Field'];
        }
    }
    
    return $categorias;
}

function obtenerJugadoresPorCategoria($categoria) {
    $db = getConnection();
    
    // Validar categoría
    $categoria = preg_replace('/[^a-zA-Z0-9_]/', '', $categoria);
    
    $query = $db->prepare("SELECT $categoria FROM posiciones_nombres WHERE $categoria IS NOT NULL");
    $query->execute();
    
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function mostrarContenido() {
    $db = getConnection(); // Asegúrate de que getConnection() devuelva una instancia de PDO
    
    $query = $db->prepare('SELECT * FROM posiciones_nombres');
    $query->execute();
    
    $tasks = $query->fetchAll(PDO::FETCH_ASSOC); // Usamos FETCH_ASSOC para obtener un array asociativo
    
    return $tasks;
}

?>
