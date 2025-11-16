<?php
namespace App\repositories;

use App\Database;
use App\models\Loan;

/**
 * Clase LoanRepository - Repositorio para préstamos
 */

class LoanRepository implements Repository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM loans ORDER BY loanDate DESC";
        $result = $this->db->query($sql);
        $loans = [];
        foreach ($result as $row) {
            $loans[] = new Loan($row);
        }
        return $loans;
    }

    public function getById($id) {
        $sql = "SELECT * FROM loans WHERE loanId = ?";
        $result = $this->db->query($sql, [$id]);
        if ($result) {
            return new Loan($result[0]);
        }
        return null;
    }

    public function create($data) {
        $dueDate = date('Y-m-d H:i:s', strtotime('+14 days'));
        $sql = "INSERT INTO loans (userId, bookId, dueDate) VALUES (?, ?, ?)";
        $loanId = $this->db->execute($sql, [
            $data['userId'],
            $data['bookId'],
            $dueDate
        ]);
        $data['loanId'] = $loanId;
        $data['dueDate'] = $dueDate;
        return new Loan($data);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $sql = "UPDATE loans SET " . implode(', ', $fields) . " WHERE loanId = ?";
        $affectedRows = $this->db->execute($sql, $values);
        return $affectedRows > 0;
    }

    public function delete($id) {
        $sql = "DELETE FROM loans WHERE loanId = ?";
        $affectedRows = $this->db->execute($sql, [$id]);
        return $affectedRows > 0;
    }

    // Métodos específicos
    public function getAllWithDetails() {
        $sql = "SELECT l.*, u.username, b.bookTitle FROM loans l
                JOIN users u ON l.userId = u.userId
                JOIN books b ON l.bookId = b.bookId
                WHERE l.status = 'active'
                ORDER BY l.loanDate DESC";
        return $this->db->query($sql);
    }

    public function getAllHistory() {
        $sql = "SELECT l.*, u.username, b.bookTitle FROM loans l
                JOIN users u ON l.userId = u.userId
                JOIN books b ON l.bookId = b.bookId
                ORDER BY l.loanDate DESC";
        return $this->db->query($sql);
    }

    public function getByIdWithDetails($id) {
        $sql = "SELECT l.*, u.username, b.bookTitle FROM loans l
                JOIN users u ON l.userId = u.userId
                JOIN books b ON l.bookId = b.bookId
                WHERE l.loanId = ?";
        $result = $this->db->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function returnLoan($loanId) {
        $sql = "UPDATE loans SET status = 'returned', returnDate = CURRENT_TIMESTAMP WHERE loanId = ?";
        $affectedRows = $this->db->execute($sql, [$loanId]);
        return $affectedRows > 0;
    }

    public function getUserActiveLoans($userId) {
        $sql = "SELECT l.*, b.bookTitle FROM loans l
                JOIN books b ON l.bookId = b.bookId
                WHERE l.userId = ? AND l.status = 'active'";
        return $this->db->query($sql, [$userId]);
    }
}
?>
