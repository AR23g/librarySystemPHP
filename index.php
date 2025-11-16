<?php
/**
 * Punto de entrada principal de la aplicación
 * Gestiona el enrutamiento a controladores
 */

session_start();

// aqui se define las  rutas base
define('BASE_PATH', __DIR__);
define('MODELS_PATH', BASE_PATH . '/app/models/');
define('CONTROLLERS_PATH', BASE_PATH . '/app/controllers/');
define('VIEWS_PATH', BASE_PATH . '/app/views/');
define('REPOSITORIES_PATH', BASE_PATH . '/app/repositories/');

// Sistema de autoload simple
spl_autoload_register(function ($className) {
    // Convertir namespace a ruta de archivo
    if (strpos($className, 'App\\') === 0) {
        $relativePath = str_replace('App\\', '', $className);
        $fileName = BASE_PATH . '/app/' . str_replace('\\', '/', $relativePath) . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }
});

// Incluir configuración
require_once BASE_PATH . '/config/database.php';
// Interfaces y classes de repositories (orden de dependencia)
require_once BASE_PATH . '/app/repositories/Repository.php';
require_once BASE_PATH . '/app/repositories/IBookRepository.php';
require_once BASE_PATH . '/app/repositories/IAuthorRepository.php';
// Repositories concretos
require_once BASE_PATH . '/app/repositories/BookRepository.php';
require_once BASE_PATH . '/app/repositories/AuthorRepository.php';
require_once BASE_PATH . '/app/repositories/UserRepository.php';
require_once BASE_PATH . '/app/repositories/LoanRepository.php';
require_once BASE_PATH . '/app/Router.php';

// aqui se obtiene una  ruta solicitada
$request = trim($_GET['route'] ?? 'auth/login');
if (empty($request)) {
    $request = 'auth/login';
}
$method = $_SERVER['REQUEST_METHOD'];

// Crear e inicializar router
$router = new Router();

// Definir rutas
$router->get('auth/login', 'AuthController', 'login');
$router->post('auth/login', 'AuthController', 'loginProcess');
$router->get('auth/register', 'AuthController', 'register');
$router->post('auth/register', 'AuthController', 'registerProcess');
$router->get('auth/logout', 'AuthController', 'logout');

// Rutas de libros
$router->get('books/list', 'BookController', 'list');
$router->get('books/create', 'BookController', 'create');
$router->post('books/store', 'BookController', 'store');
$router->get('books/edit/:id', 'BookController', 'edit');
$router->post('books/update/:id', 'BookController', 'update');
$router->get('books/delete/:id', 'BookController', 'delete');

// Rutas de autores
$router->get('authors/list', 'AuthorController', 'list');
$router->get('authors/create', 'AuthorController', 'create');
$router->post('authors/store', 'AuthorController', 'store');
$router->get('authors/edit/:id', 'AuthorController', 'edit');
$router->post('authors/update/:id', 'AuthorController', 'update');
$router->get('authors/delete/:id', 'AuthorController', 'delete');

// Rutas de préstamos
$router->get('loans/list', 'LoanController', 'list');
$router->get('loans/create', 'LoanController', 'create');
$router->post('loans/store', 'LoanController', 'store');
$router->get('loans/return/:id', 'LoanController', 'returnLoan');
$router->post('loans/processReturn/:id', 'LoanController', 'processReturn');

// Ruta de dashboard
$router->get('dashboard', 'DashboardController', 'index');


// Procesar ruta
$router->dispatch($request, $method);
?>
