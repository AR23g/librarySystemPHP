<?php
namespace App\repositories;

use App\Database;
use App\models\User;

/**
 * Clase UserRepository - Repositorio para usuarios
 */

class UserRepository implements Repository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY username ASC";
        $result = $this->db->query($sql);
        $users = [];
        foreach ($result as $row) {
            $users[] = new User($row);
        }
        return $users;
    }

    public function getById($id) {
        $sql = "SELECT * FROM users WHERE userId = ?";
        $result = $this->db->query($sql, [$id]);
        if ($result) {
            return new User($result[0]);
        }
        return null;
    }

    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $userId = $this->db->execute($sql, [
            $data['username'],
            $data['email'],
            $hashedPassword
        ]);
        $data['userId'] = $userId;
        unset($data['password']); // No devolver contraseña
        return new User($data);
    }

    public function update($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE userId = ?";
        $affectedRows = $this->db->execute($sql, $values);
        return $affectedRows > 0;
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE userId = ?";
        $affectedRows = $this->db->execute($sql, [$id]);
        return $affectedRows > 0;
    }

    // Métodos específicos
    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $result = $this->db->query($sql, [$username]);
        return $result ? $result[0] : null;
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $result = $this->db->query($sql, [$email]);
        return $result ? $result[0] : null;
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
?>
