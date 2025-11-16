<?php
namespace App\repositories;

use App\Database;
use App\models\Author;
use App\models\Book;

/**
 * Clase AuthorRepository - Implementación concreta del repositorio de autores
 */

class AuthorRepository implements IAuthorRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM authors ORDER BY authorName ASC";
        return $this->db->query($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM authors WHERE authorId = ?";
        $result = $this->db->query($sql, [$id]);
        if ($result) {
            return new Author($result[0]);
        }
        return null;
    }

    public function create($data) {
        $sql = "INSERT INTO authors (authorName, biography) VALUES (?, ?)";
        $authorId = $this->db->execute($sql, [
            $data['authorName'],
            $data['biography'] ?? ''
        ]);
        $data['authorId'] = $authorId;
        return new Author($data);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $sql = "UPDATE authors SET " . implode(', ', $fields) . " WHERE authorId = ?";
        $affectedRows = $this->db->execute($sql, $values);
        return $affectedRows > 0;
    }

    public function delete($id) {
        $sql = "DELETE FROM authors WHERE authorId = ?";
        $affectedRows = $this->db->execute($sql, [$id]);
        return $affectedRows > 0;
    }

    public function hasBooks($authorId) {
        $sql = "SELECT COUNT(*) as count FROM books WHERE authorId = ?";
        $result = $this->db->query($sql, [$authorId]);
        return $result[0]['count'] > 0;
    }

    // Método para obtener autor por nombre
    public function getByName($authorName) {
        $sql = "SELECT * FROM authors WHERE authorName = ?";
        $result = $this->db->query($sql, [$authorName]);
        if ($result) {
            return new Author($result[0]);
        }
        return null;
    }
}
?>
