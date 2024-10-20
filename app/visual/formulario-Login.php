<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="router" method="POST">
    <input type="hidden" name="action" value="Login"> 

    <label for="username">Usuario:</label>
    <input type="text" name="username" id="username" required><br><br>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" value="Iniciar Sesion">
</form>
</body>
</html>
