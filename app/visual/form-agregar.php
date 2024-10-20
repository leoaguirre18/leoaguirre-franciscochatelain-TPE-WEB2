<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Jugador</title>
</head>
<body>
    <form action="router" method="POST">
        <input type="hidden" name="action" value="agregar"> 
        <label>Nombre completo:</label>
        <input type="text" name="jugador-nombre" required>
        <br>
        <label>Categoria:</label>
        <select name="categoria" required>
            <option value="Arqueros">Arqueros</option>
            <option value="Defensores">Defensores</option>
            <option value="Mediocampistas">Mediocampistas</option>
            <option value="Delanteros">Delanteros</option>
        </select>
        <br>
        <input type="submit" value="Guardar">
    </form>
</body>
</html>