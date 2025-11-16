<?php
namespace App\models;

/**
 * Modelo User - Contenedor de datos para usuarios
 * Solo almacena atributos y métodos getter/setter
 */

class User {
    private $userId;
    private $username;
    private $email;
    private $password;
    private $createdAt;

    // Constructor opcional
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    // Establecer datos desde array
    public function setData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters
    public function getUserId() { return $this->userId; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters
    public function setUserId($userId) { $this->userId = $userId; }
    public function setUsername($username) { $this->username = $username; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
}
?>
