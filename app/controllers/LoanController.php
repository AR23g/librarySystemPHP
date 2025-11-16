<?php
// controlador de préstamos
class LoanController {
    private $loanRepository;
    private $bookRepository;

    // constructor, carga repos y revisa login
    public function __construct() {
        $this->loanRepository = new \App\repositories\LoanRepository();
        $this->bookRepository = new \App\repositories\BookRepository();
        $this->checkAuth();
    }

    // si no hay sesión, manda al login
    private function checkAuth() {
        if (!isset($_SESSION['userId'])) {
            header('Location: ?route=auth/login');
            exit;
        }
    }

    // lista préstamos
    public function list() {
        $loans = $this->loanRepository->getAllWithDetails();
        require VIEWS_PATH . 'loans/list.php';
    }

    // muestra form para crear préstamo
    public function create() {
        $books = $this->bookRepository->getAllWithAuthors();
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        require VIEWS_PATH . 'loans/create.php';
    }

    // guarda préstamo nuevo
    public function store() {
        $userId = $_SESSION['userId'];
        $bookId = $_POST['bookId'] ?? '';

        if (empty($bookId)) {
            $_SESSION['error'] = 'elige un libro';
            header('Location: ?route=loans/create');
            exit;
        }

        $book = $this->bookRepository->getById($bookId);

        if (!$book || $book->getAvailableCopies() <= 0) {
            $_SESSION['error'] = 'no hay copias';
            header('Location: ?route=loans/create');
            exit;
        }

        try {
            $this->loanRepository->create([
                'userId' => $userId,
                'bookId' => $bookId
            ]);
            $this->bookRepository->updateAvailableCopies($bookId, -1);
            $_SESSION['success'] = 'préstamo ok';
            header('Location: ?route=loans/list');
        } catch (Exception $e) {
            $_SESSION['error'] = 'error al registrar';
            header('Location: ?route=loans/create');
        }
        exit;
    }

    // muestra form para devolver préstamo
    public function returnLoan() {
        $loanId = $_GET['id'] ?? '';
        $loan = $this->loanRepository->getByIdWithDetails($loanId);

        if (!$loan) {
            $_SESSION['error'] = 'no existe préstamo';
            header('Location: ?route=loans/list');
            exit;
        }

        require VIEWS_PATH . 'loans/return.php';
    }

    // procesa devolución
    public function processReturn() {
        $loanId = $_GET['id'] ?? '';
        $loan = $this->loanRepository->getByIdWithDetails($loanId);

        if (!$loan) {
            $_SESSION['error'] = 'no existe préstamo';
            header('Location: ?route=loans/list');
            exit;
        }

        try {
            $this->loanRepository->returnLoan($loanId);
            $this->bookRepository->updateAvailableCopies($loan['bookId'], 1);
            $_SESSION['success'] = 'devolución ok';
            header('Location: ?route=loans/list');
        } catch (Exception $e) {
            $_SESSION['error'] = 'error al devolver';
            header('Location: ?route=loans/return/' . $loanId);
        }
        exit;
    }
}
