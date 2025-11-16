<?php
/**
 * Vista del registro
 * Formulario de registro de nuevos usuarios
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro - librarySystemPHP</title>
</head>
<body>
    <header>
        <h1>librarySystemPHP</h1>
    </header>

    <div class="container">
        <div class="card" style="max-width: 400px; margin: 50px auto;">
            <h2>Crear Nueva Cuenta</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="?route=auth/register">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="passwordConfirm">Confirmar Contraseña</label>
                    <input type="password" id="passwordConfirm" name="passwordConfirm" required>
                </div>

                <button type="submit">Registrarse</button>
            </form>

            <p style="margin-top: 15px; text-align: center;">
                ¿Ya tienes cuenta? <a href="?route=auth/login" style="color: #3498db;">Inicia sesión aquí</a>
            </p>
        </div>
    </div>

   
</body>
</html>
