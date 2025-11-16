<?php
/**
 * Vista Login
 * Formulario de autenticación de usuarios
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - librarySystemPHP</title>
</head>
<body>
    <header>
        <h1>librarySystemPHP</h1>
    </header>

    <div class="container">
        <div class="card" style="max-width: 400px; margin: 50px auto;">
            <h2>Iniciar Sesión</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="?route=auth/login">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Ingresar</button>
            </form>

            
        </div>
    </div>


</body>
</html>
