<?php
namespace App\models;

/**
 * Modelo Loan - Contenedor de datos para préstamos
 * Solo almacena atributos y métodos getter/setter
 */

class Loan {
    private $loanId;
    private $userId;
    private $bookId;
    private $loanDate;
    private $returnDate;
    private $dueDate;
    private $status;
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
    public function getLoanId() { return $this->loanId; }
    public function getUserId() { return $this->userId; }
    public function getBookId() { return $this->bookId; }
    public function getLoanDate() { return $this->loanDate; }
    public function getReturnDate() { return $this->returnDate; }
    public function getDueDate() { return $this->dueDate; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters
    public function setLoanId($loanId) { $this->loanId = $loanId; }
    public function setUserId($userId) { $this->userId = $userId; }
    public function setBookId($bookId) { $this->bookId = $bookId; }
    public function setLoanDate($loanDate) { $this->loanDate = $loanDate; }
    public function setReturnDate($returnDate) { $this->returnDate = $returnDate; }
    public function setDueDate($dueDate) { $this->dueDate = $dueDate; }
    public function setStatus($status) { $this->status = $status; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
}
?>
