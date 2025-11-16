<?php
/**
 * Vista del crear un préstamo
 * Formulario para crear nuevo préstamo
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Préstamo - Sistema de Biblioteca</title>
</head>
<body>
    <header>
        <h1>Sistema de Biblioteca</h1>
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
            <h2>Crear Nuevo Préstamo</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="?route=loans/store">
                <div class="form-group">
                    <label for="bookId">Libro</label>
                    <select id="bookId" name="bookId" required>
                        <option value="">Selecciona un libro</option>
                        <?php foreach ($books as $book): ?>
                            <?php if ($book['availableCopies'] > 0): ?>
                                <option value="<?php echo $book['bookId']; ?>">
                                    <?php echo $book['bookTitle']; ?> - <?php echo $book['authorName']; ?> (<?php echo $book['availableCopies']; ?> disponible)
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit">Registrar Préstamo</button>
                <a href="?route=loans/list" style="margin-left: 10px; text-decoration: none; color: #3498db;">Cancelar</a>
            </form>
        </div>
    </div>

   
</body>
</html>
