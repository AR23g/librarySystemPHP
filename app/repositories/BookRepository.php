<?php
namespace App\repositories;

use App\Database;
use App\models\Book;

/**
 * Repositorio para gestión de libros en base de datos
 * Implementa la interfaz IBookRepository para operaciones CRUD de libros
 * Centraliza toda lógica de acceso a datos relacionada con libros
 */
class BookRepository implements IBookRepository {
    private $db;

    /**
     * Inicializa el repositorio con conexión a base de datos
     * Usa patrón Singleton para garantizar una sola instancia de conexión
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Obtiene todos los libros (sin autores) - Implementa Repository
     * @return array Lista de objetos Book
     */
    public function getAll() {
        $sql = "SELECT * FROM books ORDER BY bookTitle ASC";
        $result = $this->db->query($sql);
        $books = [];
        foreach ($result as $row) {
            $books[] = new Book($row);
        }
        return $books;
    }

    /**
     * Obtiene un libro por ID - Implementa Repository
     * @param int $id ID del libro
     * @return Book|null Libro encontrado o null
     */
    public function getById($id) {
        $sql = "SELECT * FROM books WHERE bookId = ?";
        $result = $this->db->query($sql, [$id]);
        if ($result) {
            return new Book($result[0]);
        }
        return null;
    }

    /**
     * Crea un nuevo libro - Implementa Repository
     * @param array $data Datos del libro
     * @return Book Libro creado con ID asignado
     */
    public function create($data) {
        $defaultData = [
            'totalCopies' => 1,
            'publicationYear' => null
        ];
        $data = array_merge($defaultData, $data);
        $availableCopies = $data['totalCopies']; // Inicialmente todas disponibles

        $sql = "INSERT INTO books (bookTitle, authorId, isbn, publicationYear, totalCopies, availableCopies)
                VALUES (?, ?, ?, ?, ?, ?)";
        $bookId = $this->db->execute($sql, [
            $data['bookTitle'],
            $data['authorId'],
            $data['isbn'],
            $data['publicationYear'],
            $data['totalCopies'],
            $availableCopies
        ]);

        $data['bookId'] = $bookId;
        return new Book($data);
    }

    /**
     * Actualiza un libro - Implementa Repository
     * @param int $id ID del libro
     * @param array $data Datos a actualizar
     * @return bool Verdadero si fue actualizado exitosamente
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id; // Para WHERE

        $sql = "UPDATE books SET " . implode(', ', $fields) . " WHERE bookId = ?";
        $affectedRows = $this->db->execute($sql, $values);
        return $affectedRows > 0;
    }

    /**
     * Elimina un libro - Implementa Repository
     * @param int $id ID del libro
     * @return bool Verdadero si fue eliminado exitosamente
     */
    public function delete($id) {
        $sql = "DELETE FROM books WHERE bookId = ?";
        $affectedRows = $this->db->execute($sql, [$id]);
        return $affectedRows > 0;
    }

    /**
     * Obtiene todos los libros con información de autores - Método específico
     * @return array Lista de arrays con datos de libros y autores
     */
    public function getAllWithAuthors() {
        $sql = "SELECT b.*, a.authorName FROM books b
                LEFT JOIN authors a ON b.authorId = a.authorId
                ORDER BY b.bookTitle ASC";
        return $this->db->query($sql);
    }

    /**
     * Actualiza copias disponibles de un libro - Método específico
     * @param int $bookId ID del libro
     * @param int $change Cambio en copias (positivo para aumentar, negativo para disminuir)
     * @return bool Verdadero si se actualizó correctamente
     */
    public function updateAvailableCopies($bookId, $change) {
        $sql = "UPDATE books SET availableCopies = availableCopies + ? WHERE bookId = ?";
        $affectedRows = $this->db->execute($sql, [$change, $bookId]);
        return $affectedRows > 0;
    }

    // Método para obtener libro por ISBN
    public function getByIsbn($isbn) {
        $sql = "SELECT * FROM books WHERE isbn = ?";
        $result = $this->db->query($sql, [$isbn]);
        if ($result) {
            return new Book($result[0]);
        }
        return null;
    }
}
?>
