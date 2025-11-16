<?php
/**
 * Vista Lista de Préstamos
 * Listado de todos los préstamos
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Préstamos - Sistema de Biblioteca</title>
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
            <h2>Préstamos Activos</h2>

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
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($loans)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No hay préstamos activos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($loans as $loan): ?>
                            <tr>
                                <td><?php echo $loan['loanId']; ?></td>
                                <td><?php echo $loan['username']; ?></td>
                                <td><?php echo $loan['bookTitle']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($loan['loanDate'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($loan['dueDate'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="?route=loans/return/<?php echo $loan['loanId']; ?>" class="btn-success">Devolver</a>
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
