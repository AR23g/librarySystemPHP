<?php
/**
 * Vista Lista de Autores
 * Listado de todos los autores
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Autores - Sistema de Biblioteca</title>
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
        <div class="card">
            <h2>Autores</h2>

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
                        <th>Nombre</th>
                        <th>Biografía</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($authors)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay autores registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($authors as $author): ?>
                            <tr>
                                <td><?php echo $author['authorId']; ?></td>
                                <td><?php echo $author['authorName']; ?></td>
                                <td><?php echo substr($author['biography'], 0, 50) . (strlen($author['biography']) > 50 ? '...' : ''); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="?route=authors/edit/<?php echo $author['authorId']; ?>">Editar</a>
                                        <a href="?route=authors/delete/<?php echo $author['authorId']; ?>" class="btn-delete" onclick="return confirm('¿Está seguro?')">Eliminar</a>
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
