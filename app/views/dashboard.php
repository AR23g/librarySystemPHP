<?php
/**
 * Vista Dashboard
 * Página principal después de autenticarse
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - librarySystemPHP</title>
</head>
<body>
    <header>
        <h1>librarySystemPHP</h1>
        <p>Bienvenido, <?php echo $_SESSION['username']; ?></p>
    </header>

    <nav>
        <a href="?route=dashboard">Inicio</a>
        <a href="?route=books/list">Libros</a>
        <a href="?route=books/create">Agregar Libro</a>
        <a href="?route=authors/list">Autores</a>
        <a href="?route=authors/create">Agregar Autor</a>
        <a href="?route=loans/list">Préstamos</a>
        <a href="?route=loans/create">Nuevo Préstamo</a>
        <a href="?route=auth/logout">Salir</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Bienvenido al librarySystemPHP</h2>
            <p>Este sistema permite administrar libros, autores y préstamos de forma sencilla.</p>
            <p>Usa el menú de navegación para acceder a las diferentes secciones.</p>
        </div>
    </div>

   
</body>
</html>
