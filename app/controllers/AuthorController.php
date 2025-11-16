<?php
/*
 Controlador Author
  Es el que Gestiona las operaciones CRUD de autores
 */

class AuthorController {
    private $authorRepository;

    /*
      Constructor del controlador
     */
    public function __construct() {
        $this->authorRepository = new \App\repositories\AuthorRepository();
        $this->checkAuth();
    }

    /*
      Verifica si el usuario está autenticado
     */
    private function checkAuth() {
        if (!isset($_SESSION['userId'])) {
            header('Location: ?route=auth/login');
            exit;
        }
    }

    /*
      Lista todos los autores
     */
    public function list() {
        $authors = $this->authorRepository->getAll();
        require_once VIEWS_PATH . 'authors/list.php';
    }

    /*
      Muestra formulario para crear autor
     */
    public function create() {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
        unset($_SESSION['error']);
        require_once VIEWS_PATH . 'authors/create.php';
    }

    /*
      Almacena un nuevo autor en la base de datos
     */
    public function store() {
        $authorName = $_POST['authorName'] ?? '';
        $biography = $_POST['biography'] ?? '';

        if (empty($authorName)) {
            $_SESSION['error'] = 'El nombre del autor es requerido';
            header('Location: ?route=authors/create');
            exit;
        }

        // la verificacion de que  si el autor ya existe
        if ($this->authorRepository->getByName($authorName)) {
            $_SESSION['error'] = 'Ya existe un autor con ese nombre';
            header('Location: ?route=authors/create');
            exit;
        }

        try {
            $this->authorRepository->create([
                'authorName' => $authorName,
                'biography' => $biography
            ]);
            $_SESSION['success'] = 'Autor creado exitosamente';
            header('Location: ?route=authors/list');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el autor: ' . $e->getMessage();
            header('Location: ?route=authors/create');
        }
        exit;
    }

    /**
     * Muestra el formulario para editar autor
     */
    public function edit() {
        $authorId = $_GET['id'] ?? '';
        $author = $this->authorRepository->getById($authorId);
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
        unset($_SESSION['error']);

        if (!$author) {
            $_SESSION['error'] = 'Autor no encontrado';
            header('Location: ?route=authors/list');
            exit;
        }

        // Convertir objeto a array para la vista
        $author = [
            'authorId' => $author->getAuthorId(),
            'authorName' => $author->getAuthorName(),
            'biography' => $author->getBiography()
        ];

        require_once VIEWS_PATH . 'authors/edit.php';
    }

    /**
     * Aqui se  Actualiza un autor en la base de datos
     */
    public function update() {
        $authorId = $_GET['id'] ?? '';
        $authorName = $_POST['authorName'] ?? '';
        $biography = $_POST['biography'] ?? '';

        if (empty($authorName)) {
            $_SESSION['error'] = 'El nombre del autor es requerido';
            header('Location: ?route=authors/edit/' . $authorId);
            exit;
        }

        try {
            $this->authorRepository->update($authorId, [
                'authorName' => $authorName,
                'biography' => $biography
            ]);
            $_SESSION['success'] = 'Autor actualizado exitosamente';
            header('Location: ?route=authors/list');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el autor';
            header('Location: ?route=authors/edit/' . $authorId);
        }
        exit;
    }

    /**
     *  Esta parte Elimina un autor
     */
    public function delete() {
        $authorId = $_GET['id'] ?? '';

        if (empty($authorId)) {
            $_SESSION['error'] = 'ID de autor no válido';
            header('Location: ?route=authors/list');
            exit;
        }

        if ($this->authorRepository->hasBooks($authorId)) {
            $_SESSION['error'] = 'No se puede eliminar un autor que tiene libros asociados';
            header('Location: ?route=authors/list');
            exit;
        }

        try {
            $this->authorRepository->delete($authorId);
            $_SESSION['success'] = 'Autor eliminado exitosamente';
            header('Location: ?route=authors/list');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el autor';
            header('Location: ?route=authors/list');
        }
        exit;
    }
}
?>
