<?php namespace App;
define('DB_PATH', __DIR__ . '/../database/');

/**
 * Clase Database - Singleton para conexión a base de datos
 * Gestiona la conexión única con patrón Singleton
 */

class Database {
    private static $instance = null;
    private $connection;

    /**
     * Patrรณn Singleton - obtiene instancia de base de datos
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Constructor - inicializa conexiรณn SQLite
     */
    private function __construct() {
        $dbPath = DB_PATH . 'biblioteca.db';

        try {
            $this->connection = new \PDO('sqlite:' . $dbPath);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Crear tablas si no existen
            $this->createTables();
        } catch (\PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }

    /**
     * Crea las tablas necesarias en la base de datos
     
     */
    private function createTables() {
        // Tabla de usuarios
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS users (
                userId INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Tabla de autores
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS authors (
                authorId INTEGER PRIMARY KEY AUTOINCREMENT,
                authorName TEXT NOT NULL UNIQUE,
                biography TEXT,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Tabla de libros
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS books (
                bookId INTEGER PRIMARY KEY AUTOINCREMENT,
                bookTitle TEXT NOT NULL,
                authorId INTEGER NOT NULL,
                isbn TEXT UNIQUE NOT NULL,
                publicationYear INTEGER,
                totalCopies INTEGER DEFAULT 1,
                availableCopies INTEGER DEFAULT 1,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (authorId) REFERENCES authors(authorId) ON DELETE CASCADE
            )
        ");

        // Tabla de prestamos
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS loans (
                loanId INTEGER PRIMARY KEY AUTOINCREMENT,
                userId INTEGER NOT NULL,
                bookId INTEGER NOT NULL,
                loanDate DATETIME DEFAULT CURRENT_TIMESTAMP,
                returnDate DATETIME,
                dueDate DATETIME,
                status TEXT DEFAULT 'active',
                FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
                FOREIGN KEY (bookId) REFERENCES books(bookId) ON DELETE CASCADE
            )
        ");

        $this->createDefaultAdmin();
    }

    /**
     * Crea usuario admin por defecto si no existe
     */
    private function createDefaultAdmin() {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['admin']);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result['count'] == 0) {
                $hashedPassword = password_hash('1234', PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute(['admin', 'admin@biblioteca.local', $hashedPassword]);
            }
        } catch (\PDOException $e) {
            // Silenciar errores en la creaciรณn de admin
        }
    }

    /**
     * Obtiene la conexiรณn PDO
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Ejecuta una consulta SELECT
     * @param string $sql Consulta SQL
     * @param array $params Parรกmetros preparados
     * @return array Resultados
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception('Error en consulta: ' . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta de modificaciรณn (INSERT, UPDATE, DELETE)
     * @param string $sql Consulta SQL
     * @param array $params Parรกmetros preparados
     * @return int ID insertado o filas afectadas
     */
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $this->connection->lastInsertId() ?: $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \Exception('Error en ejecución: ' . $e->getMessage());
        }
    }
}

// Inicia la base de datos
$db = Database::getInstance();
?>
