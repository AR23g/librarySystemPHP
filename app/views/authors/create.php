<?php
/**
 * Vista del crear un autor
 * Formulario para crear nuevo autor
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Autor - librarySystemPHP</title>
</head>
<body>
    <header>
        <h1>librarySystemPHP</h1>
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
        <div class="card" style="max-width: 600px;">
            <h2>Crear Nuevo Autor</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="?route=authors/store">
                <div class="form-group">
                    <label for="authorName">Nombre del Autor</label>
                    <input type="text" id="authorName" name="authorName" required>
                </div>

                <div class="form-group">
                    <label for="biography">Biografía</label>
                    <textarea id="biography" name="biography" placeholder="Escribe la biografía del autor (opcional)"></textarea>
                </div>

                <button type="submit">Crear Autor</button>
                <a href="?route=authors/list" style="margin-left: 10px; text-decoration: none; color: #3498db;">Cancelar</a>
            </form>
        </div>
    </div>

   
</body>
</html>
