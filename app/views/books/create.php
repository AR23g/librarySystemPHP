<?php
/**
 * Vista Create Book
 * Formulario para crear nuevo libro
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Libro - librarySystemPHP</title>
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
            <h2>Crear Nuevo Libro</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="?route=books/store">
                <div class="form-group">
                    <label for="bookTitle">Título del Libro</label>
                    <input type="text" id="bookTitle" name="bookTitle" required>
                </div>

                <div class="form-group">
                    <label for="authorId">Autor</label>
                    <select id="authorId" name="authorId" required>
                        <option value="">Selecciona un autor</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?php echo $author['authorId']; ?>">
                                <?php echo $author['authorName']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" required>
                </div>

                <div class="form-group">
                    <label for="publicationYear">Año de Publicación</label>
                    <input type="number" id="publicationYear" name="publicationYear">
                </div>

                <div class="form-group">
                    <label for="totalCopies">Número de Copias</label>
                    <input type="number" id="totalCopies" name="totalCopies" value="1" min="1" required>
                </div>

                <button type="submit">Crear Libro</button>
                <a href="?route=books/list" style="margin-left: 10px; text-decoration: none; color: #3498db;">Cancelar</a>
            </form>
        </div>
    </div>

    
</body>
</html>
