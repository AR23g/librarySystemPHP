<?php
/**
 * Vista Lista de Libros
 * Listado de todos los libros
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Libros - librarySystemPHP</title>
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
        <div class="card">
            <h2>Libros</h2>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Año</th>
                        <th>Disponibles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay libros registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?php echo $book['bookId']; ?></td>
                                <td><?php echo $book['bookTitle']; ?></td>
                                <td><?php echo $book['authorName']; ?></td>
                                <td><?php echo $book['isbn']; ?></td>
                                <td><?php echo $book['publicationYear']; ?></td>
                                <td><?php echo $book['availableCopies']; ?>/<?php echo $book['totalCopies']; ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="?route=books/edit/<?php echo $book['bookId']; ?>">Editar</a>
                                        <a href="?route=books/delete/<?php echo $book['bookId']; ?>" class="btn-delete" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
</body>
</html>
