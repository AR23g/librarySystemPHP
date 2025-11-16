<?php
/**
 * Vista devolver un préstamo
 * Confirmación de devolución de préstamo
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Devolver Préstamo - Sistema de Biblioteca</title>
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
        <div class="card" style="max-width: 600px; margin: 50px auto;">
            <h2>Devolver Préstamo</h2>

            <p>¿Confirma la devolución del siguiente préstamo?</p>

            <table>
                <tr>
                    <td><strong>Usuario:</strong></td>
                    <td><?php echo $loan['username']; ?></td>
                </tr>
                <tr>
                    <td><strong>Libro:</strong></td>
                    <td><?php echo $loan['bookTitle']; ?></td>
                </tr>
                <tr>
                    <td><strong>Fecha Préstamo:</strong></td>
                    <td><?php echo date('d/m/Y', strtotime($loan['loanDate'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Fecha Vencimiento:</strong></td>
                    <td><?php echo date('d/m/Y', strtotime($loan['dueDate'])); ?></td>
                </tr>
            </table>

            <form method="POST" action="?route=loans/processReturn/<?php echo $loan['loanId']; ?>" style="margin-top: 20px;">
                <button type="submit" class="btn-success">Confirmar Devolución</button>
                <a href="?route=loans/list" style="margin-left: 10px; text-decoration: none; color: #3498db;">Cancelar</a>
            </form>
        </div>
    </div>

   
</body>
</html>
