<?php
// controlador de libros, hace lo básico (crud)
class BookController {
    private $bookRepository;
    private $authorRepository;

    public function __construct() {
        // inicializo repos y reviso login (medio improvisado)
        $this->bookRepository = new \App\repositories\BookRepository();
        $this->authorRepository = new \App\repositories\AuthorRepository();
        $this->checkAuth();
    }

    // si no hay sesión, mando al login
    private function checkAuth() {
        if (!isset($_SESSION['userId'])) {
            $_SESSION['error'] = "login requerido";
            header("Location: ?route=auth/login");
            exit;
        }
    }

    // lista libros, si no hay pues vacío
    public function list() {
        $books = $this->bookRepository->getAllWithAuthors();
        require VIEWS_PATH . "books/list.php";
    }

    // muestra form para crear libro
    public function create() {
        $authors = $this->authorRepository->getAll();
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']); // borro error 
        require VIEWS_PATH . "books/create.php";
    }

    // guarda libro nuevo 
    public function store() {
        $input = $this->sanitizeBookInput();

        if (empty($input['bookTitle']) || empty($input['authorId']) || empty($input['isbn'])) {
            $_SESSION['error'] = "faltan campos";
            header("Location: ?route=books/create");
            exit;
        }

        if (!$this->authorRepository->getById($input['authorId'])) {
            $_SESSION['error'] = "autor no existe";
            header("Location: ?route=books/create");
            exit;
        }

        if ($this->bookRepository->getByIsbn($input['isbn'])) {
            $_SESSION['error'] = "isbn repetido";
            header("Location: ?route=books/create");
            exit;
        }

        if ($input['totalCopies'] < 1 || $input['totalCopies'] > 1000) {
            $_SESSION['error'] = "copias fuera de rango";
            header("Location: ?route=books/create");
            exit;
        }

        try {
            $this->bookRepository->create($input);
            $_SESSION['success'] = "libro guardado";
            header("Location: ?route=books/list");
        } catch (Exception $e) {
            $this->dbError($e, "books/create");
        }
        exit;
    }

    // limpia datos del form (solo usado en store, update no lo usa)
    private function sanitizeBookInput() {
        return [
            "bookTitle" => trim($_POST["bookTitle"] ?? ""),
            "authorId" => (int)($_POST["authorId"] ?? 0),
            "isbn" => strtoupper(trim($_POST["isbn"] ?? "")),
            "publicationYear" => (int)($_POST["publicationYear"] ?? 0),
            "totalCopies" => (int)($_POST["totalCopies"] ?? 1)
        ];
    }

    // manejo de error genérico, no da detalles
    private function dbError(Exception $e, $redirectRoute) {
        error_log("Error BD: " . $e->getMessage());
        $_SESSION["error"] = "error interno";
        header("Location: ?route=" . $redirectRoute);
        exit;
    }

    // muestra form para editar 
    public function edit() {
        $bookId = $_GET["id"] ?? null;
        $book = $this->bookRepository->getById($bookId);
        $authors = $this->authorRepository->getAll();
        $error = $_SESSION["error"] ?? null;
        unset($_SESSION["error"]);

        if (!$book) {
            $_SESSION["error"] = "libro no encontrado";
            header("Location: ?route=books/list");
            exit;
        }

        // paso a array manualmente
        $book = [
            "bookId" => $book->getBookId(),
            "bookTitle" => $book->getBookTitle(),
            "authorId" => $book->getAuthorId(),
            "isbn" => $book->getIsbn(),
            "publicationYear" => $book->getPublicationYear(),
            "totalCopies" => $book->getTotalCopies()
        ];

        require VIEWS_PATH . "books/edit.php";
    }

    // actualiza libro (validaciones mínimas, no tan estrictas como store)
    public function update() {
        $bookId = $_GET["id"] ?? null;
        $bookTitle = $_POST["bookTitle"] ?? "";
        $authorId = $_POST["authorId"] ?? "";
        $isbn = $_POST["isbn"] ?? "";
        $publicationYear = $_POST["publicationYear"] ?? "";
        $totalCopies = $_POST["totalCopies"] ?? 1;

        if (!$bookTitle || !$authorId || !$isbn) {
            $_SESSION["error"] = "faltan datos";
            header("Location: ?route=books/edit/" . $bookId);
            exit;
        }

        try {
            $this->bookRepository->update($bookId, [
                "bookTitle" => $bookTitle,
                "authorId" => $authorId,
                "isbn" => $isbn,
                "publicationYear" => $publicationYear,
                "totalCopies" => $totalCopies
            ]);
            $_SESSION["success"] = "libro actualizado";
            header("Location: ?route=books/list");
        } catch (Exception $e) {
            $_SESSION["error"] = "error al actualizar";
            header("Location: ?route=books/edit/" . $bookId);
        }
        exit;
    }

    // borra libro
    public function delete() {
        $bookId = $_GET["id"] ?? null;

        if (!$bookId) {
            $_SESSION["error"] = "id inválido";
            header("Location: ?route=books/list");
            exit;
        }

        try {
            $this->bookRepository->delete($bookId);
            $_SESSION["success"] = "libro eliminado";
            header("Location: ?route=books/list");
        } catch (Exception $e) {
            $_SESSION["error"] = "error al eliminar";
            header("Location: ?route=books/list");
        }
        exit;
    }
}
