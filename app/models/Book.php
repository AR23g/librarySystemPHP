<?php
namespace App\models;

/**
 * Modelo Book - Contenedor de datos para libros
 * Solo almacena atributos y métodos getter/setter
 */

class Book {
    private $bookId;
    private $bookTitle;
    private $authorId;
    private $isbn;
    private $publicationYear;
    private $totalCopies;
    private $availableCopies;
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
    public function getBookId() { return $this->bookId; }
    public function getBookTitle() { return $this->bookTitle; }
    public function getAuthorId() { return $this->authorId; }
    public function getIsbn() { return $this->isbn; }
    public function getPublicationYear() { return $this->publicationYear; }
    public function getTotalCopies() { return $this->totalCopies; }
    public function getAvailableCopies() { return $this->availableCopies; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters - con validación básica
    public function setBookId($bookId) { $this->bookId = (int)$bookId; }
    public function setBookTitle($bookTitle) { $this->bookTitle = trim($bookTitle); }
    public function setAuthorId($authorId) { $this->authorId = (int)$authorId; }
    public function setIsbn($isbn) { $this->isbn = trim(strtoupper($isbn)); }
    public function setPublicationYear($publicationYear) { $this->publicationYear = (int)$publicationYear; }
    public function setTotalCopies($totalCopies) { $this->totalCopies = (int)$totalCopies; }
    public function setAvailableCopies($availableCopies) { $this->availableCopies = (int)$availableCopies; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
}
?>
