<?php
namespace FOBMS;

class Borrowing {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function borrowBook($userId, $bookId) {
        try {
            $this->db->beginTransaction();

            // Check if book is available
            $sql = "SELECT available FROM books WHERE id = ?";
            $this->db->query($sql);
            $this->db->bind(1, $bookId);
            $book = $this->db->fetch();

            if (!$book || $book['available'] <= 0) {
                throw new \Exception("Book is not available for borrowing");
            }

            // Check if user has any overdue books
            $sql = "SELECT COUNT(*) as count FROM borrowings 
                    WHERE user_id = ? AND return_date IS NULL 
                    AND due_date < CURRENT_DATE()";
            $this->db->query($sql);
            $this->db->bind(1, $userId);
            $result = $this->db->fetch();

            if ($result['count'] > 0) {
                throw new \Exception("You have overdue books. Please return them first.");
            }

            // Check if user has already borrowed this book
            $sql = "SELECT COUNT(*) as count FROM borrowings 
                    WHERE user_id = ? AND book_id = ? AND return_date IS NULL";
            $this->db->query($sql);
            $this->db->bind(1, $userId);
            $this->db->bind(2, $bookId);
            $result = $this->db->fetch();

            if ($result['count'] > 0) {
                throw new \Exception("You have already borrowed this book");
            }

            // Create borrowing record
            $sql = "INSERT INTO borrowings (user_id, book_id, borrow_date, due_date) 
                    VALUES (?, ?, CURRENT_DATE(), DATE_ADD(CURRENT_DATE(), INTERVAL 14 DAY))";
            $this->db->query($sql);
            $this->db->bind(1, $userId);
            $this->db->bind(2, $bookId);

            if (!$this->db->execute()) {
                throw new \Exception("Failed to create borrowing record");
            }

            // Update book availability
            $sql = "UPDATE books SET available = available - 1 WHERE id = ?";
            $this->db->query($sql);
            $this->db->bind(1, $bookId);

            if (!$this->db->execute()) {
                throw new \Exception("Failed to update book availability");
            }

            $this->db->commit();
            error_log("Book borrowed: User ID {$userId}, Book ID {$bookId}");
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error borrowing book: " . $e->getMessage());
            throw $e;
        }
    }

    public function returnBook($borrowingId) {
        try {
            $this->db->beginTransaction();

            // Get borrowing details
            $sql = "SELECT * FROM borrowings WHERE id = ? AND return_date IS NULL";
            $this->db->query($sql);
            $this->db->bind(1, $borrowingId);
            $borrowing = $this->db->fetch();

            if (!$borrowing) {
                throw new \Exception("Borrowing record not found or book already returned");
            }

            // Update borrowing record
            $sql = "UPDATE borrowings SET return_date = CURRENT_DATE() WHERE id = ?";
            $this->db->query($sql);
            $this->db->bind(1, $borrowingId);

            if (!$this->db->execute()) {
                throw new \Exception("Failed to update borrowing record");
            }

            // Update book availability
            $sql = "UPDATE books SET available = available + 1 WHERE id = ?";
            $this->db->query($sql);
            $this->db->bind(1, $borrowing['book_id']);

            if (!$this->db->execute()) {
                throw new \Exception("Failed to update book availability");
            }

            $this->db->commit();
            error_log("Book returned: Borrowing ID {$borrowingId}");
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error returning book: " . $e->getMessage());
            throw $e;
        }
    }

    public function getUserBorrowings($userId) {
        try {
            $sql = "SELECT b.*, bk.title, bk.author 
                    FROM borrowings b 
                    JOIN books bk ON b.book_id = bk.id 
                    WHERE b.user_id = ? 
                    ORDER BY b.borrow_date DESC";
            $this->db->query($sql);
            $this->db->bind(1, $userId);
            return $this->db->fetchAll();
        } catch (\Exception $e) {
            error_log("Error fetching user borrowings: " . $e->getMessage());
            throw new \Exception("Failed to fetch borrowings");
        }
    }

    public function getAllBorrowings($filters = []) {
        try {
            $sql = "SELECT b.*, u.name as user_name, bk.title, bk.author 
                    FROM borrowings b 
                    JOIN users u ON b.user_id = u.id 
                    JOIN books bk ON b.book_id = bk.id 
                    WHERE 1=1";
            $params = [];

            if (!empty($filters['status'])) {
                if ($filters['status'] === 'active') {
                    $sql .= " AND b.return_date IS NULL";
                } elseif ($filters['status'] === 'returned') {
                    $sql .= " AND b.return_date IS NOT NULL";
                }
            }

            if (!empty($filters['overdue'])) {
                $sql .= " AND b.return_date IS NULL AND b.due_date < CURRENT_DATE()";
            }

            $sql .= " ORDER BY b.borrow_date DESC";

            $this->db->query($sql);
            foreach ($params as $index => $param) {
                $this->db->bind($index + 1, $param);
            }

            return $this->db->fetchAll();
        } catch (\Exception $e) {
            error_log("Error fetching all borrowings: " . $e->getMessage());
            throw new \Exception("Failed to fetch borrowings");
        }
    }

    public function getOverdueBorrowings() {
        try {
            $sql = "SELECT b.*, u.name as user_name, bk.title, bk.author 
                    FROM borrowings b 
                    JOIN users u ON b.user_id = u.id 
                    JOIN books bk ON b.book_id = bk.id 
                    WHERE b.return_date IS NULL 
                    AND b.due_date < CURRENT_DATE() 
                    ORDER BY b.due_date ASC";
            $this->db->query($sql);
            return $this->db->fetchAll();
        } catch (\Exception $e) {
            error_log("Error fetching overdue borrowings: " . $e->getMessage());
            throw new \Exception("Failed to fetch overdue borrowings");
        }
    }
} 