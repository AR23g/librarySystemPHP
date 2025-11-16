<?php
/**
 * Controlador de Autenticación
 * Gestiona login, registro y cierre de sesión con validaciones
 * Incluye lógica de negocio como límites de intentos y validación de seguridad
 */

class AuthController {
    private $userRepository;
    private $maxLoginAttempts = 5;

    public function __construct() {
        $this->userRepository = new \App\repositories\UserRepository();
    }

    /**
     * Renderiza la vista de login.
     * Si hubo errores previos, los muestra al usuario.
     */
    public function login() {
        $errorMessage = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        require_once VIEWS_PATH . 'auth/login.php';
    }

    /**
     * Procesa el intento de login del usuario.
     * Valida credenciales, controla intentos fallidos y registra actividad.
     */
    public function loginProcess() {
        $username = htmlspecialchars($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Por favor ingresa tu usuario y contraseña.';
            header('Location: ?route=auth/login');
            exit;
        }

        //  Esto basicamente da Control de intentos fallidos
        $_SESSION['loginAttempts'] = ($_SESSION['loginAttempts'] ?? 0) + 1;
        if ($_SESSION['loginAttempts'] > $this->maxLoginAttempts) {
            $_SESSION['error'] = 'Demasiados intentos fallidos. Intenta más tarde.';
            header('Location: ?route=auth/login');
            exit;
        }

        $user = $this->userRepository->getByUsername($username);

        if (!$user || !$this->userRepository->verifyPassword($password, $user['password'])) {
            $_SESSION['error'] = 'Credenciales inválidas. Verifica tu usuario o contraseña.';
            header('Location: ?route=auth/login');
            exit;
        }

        // esto hace un reset de intentos al login exitoso
        $_SESSION['loginAttempts'] = 0;
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['username'] = $user['username'];
        header('Location: ?route=dashboard');
        exit;
    }

    /**
     * Renderiza la vista de registro.
     */
    public function register() {
        $errorMessage = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        require_once VIEWS_PATH . 'auth/register.php';
    }

    /**
     * Procesa el registro de un nuevo usuario.
     * Incluye validación de email, fortaleza de contraseña y duplicados.
     */
    public function registerProcess() {
        $username = htmlspecialchars($_POST['username'] ?? '');
        $emailAddress = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['passwordConfirm'] ?? '';

        if (empty($username) || empty($emailAddress) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: ?route=auth/register');
            exit;
        }

        // Validación de email con regex
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo electrónico no tiene un formato válido.';
            header('Location: ?route=auth/register');
            exit;
        }

        // Validación de fortaleza de contraseña
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula y un número.';
            header('Location: ?route=auth/register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ?route=auth/register');
            exit;
        }

        if ($this->userRepository->getByUsername($username)) {
            $_SESSION['error'] = 'El nombre de usuario ya está en uso.';
            header('Location: ?route=auth/register');
            exit;
        }

        if ($this->userRepository->getByEmail($emailAddress)) {
            $_SESSION['error'] = 'El correo electrónico ya está registrado.';
            header('Location: ?route=auth/register');
            exit;
        }

        $this->userRepository->create([
            'username' => $username,
            'email' => $emailAddress,
            'password' => $password
        ]);
        $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
        header('Location: ?route=auth/login');
        exit;
    }

    /**
     * Cierra la sesión del usuario y limpia datos.
     */
    public function logout() {
        session_destroy();
        header('Location: ?route=auth/login');
        exit;
    }
}
?>
