<?php
namespace App\models;

/**
 * Modelo Author - Contenedor de datos para autores
 * Solo almacena atributos y métodos getter/setter
 */

class Author {
    private $authorId;
    private $authorName;
    private $biography;
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
    public function getAuthorId() { return $this->authorId; }
    public function getAuthorName() { return $this->authorName; }
    public function getBiography() { return $this->biography; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters
    public function setAuthorId($authorId) { $this->authorId = $authorId; }
    public function setAuthorName($authorName) { $this->authorName = $authorName; }
    public function setBiography($biography) { $this->biography = $biography; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
}
?>
