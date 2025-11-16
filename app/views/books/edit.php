<?php
/**
 * Vista  de editar un libro
 * Formulario para editar un libro
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Libro - Sistema de Biblioteca</title>
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
            <h2>Editar Libro</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="?route=books/update/<?php echo $book['bookId']; ?>">
                <div class="form-group">
                    <label for="bookTitle">Título del Libro</label>
                    <input type="text" id="bookTitle" name="bookTitle" value="<?php echo $book['bookTitle']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="authorId">Autor</label>
                    <select id="authorId" name="authorId" required>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?php echo $author['authorId']; ?>" <?php if ($author['authorId'] == $book['authorId']) echo 'selected'; ?>>
                                <?php echo $author['authorName']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" value="<?php echo $book['isbn']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="publicationYear">Año de Publicación</label>
                    <input type="number" id="publicationYear" name="publicationYear" value="<?php echo $book['publicationYear']; ?>">
                </div>

                <div class="form-group">
                    <label for="totalCopies">Número de Copias</label>
                    <input type="number" id="totalCopies" name="totalCopies" value="<?php echo $book['totalCopies']; ?>" min="1" required>
                </div>

                <button type="submit">Actualizar Libro</button>
                <a href="?route=books/list" style="margin-left: 10px; text-decoration: none; color: #3498db;">Cancelar</a>
            </form>
        </div>
    </div>

</body>
</html>
