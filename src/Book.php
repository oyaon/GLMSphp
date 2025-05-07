<?php
namespace FOBMS;

class Book {
    private $db;
    private $id;
    private $title;
    private $author;
    private $isbn;
    private $category;
    private $quantity;
    private $available;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Validate input data
            $this->validateBookData($data);

            // Insert book
            $sql = "INSERT INTO books (title, author, isbn, category, quantity, available) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->db->query($sql);
            $this->db->bind(1, $data['title']);
            $this->db->bind(2, $data['author']);
            $this->db->bind(3, $data['isbn']);
            $this->db->bind(4, $data['category']);
            $this->db->bind(5, $data['quantity']);
            $this->db->bind(6, $data['quantity']); // Initially all books are available

            if ($this->db->execute()) {
                $this->db->commit();
                error_log("New book added: {$data['title']} by {$data['author']}");
                return $this->db->lastInsertId();
            }

            $this->db->rollback();
            return false;
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error creating book: " . $e->getMessage());
            throw new \Exception("Failed to create book");
        }
    }

    public function update($id, $data) {
        try {
            $this->db->beginTransaction();

            // Validate input data
            $this->validateBookData($data);

            // Update book
            $sql = "UPDATE books 
                    SET title = ?, author = ?, isbn = ?, category = ?, quantity = ? 
                    WHERE id = ?";
            
            $this->db->query($sql);
            $this->db->bind(1, $data['title']);
            $this->db->bind(2, $data['author']);
            $this->db->bind(3, $data['isbn']);
            $this->db->bind(4, $data['category']);
            $this->db->bind(5, $data['quantity']);
            $this->db->bind(6, $id);

            if ($this->db->execute()) {
                $this->db->commit();
                error_log("Book updated: ID {$id}");
                return true;
            }

            $this->db->rollback();
            return false;
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error updating book: " . $e->getMessage());
            throw new \Exception("Failed to update book");
        }
    }

    public function delete($id) {
        try {
            $this->db->beginTransaction();

            // Check if book is currently borrowed
            $sql = "SELECT COUNT(*) as count FROM borrowings WHERE book_id = ? AND return_date IS NULL";
            $this->db->query($sql);
            $this->db->bind(1, $id);
            $result = $this->db->fetch();

            if ($result['count'] > 0) {
                throw new \Exception("Cannot delete book that is currently borrowed");
            }

            // Delete book
            $sql = "DELETE FROM books WHERE id = ?";
            $this->db->query($sql);
            $this->db->bind(1, $id);

            if ($this->db->execute()) {
                $this->db->commit();
                error_log("Book deleted: ID {$id}");
                return true;
            }

            $this->db->rollback();
            return false;
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error deleting book: " . $e->getMessage());
            throw new \Exception("Failed to delete book");
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM books WHERE id = ?";
            $this->db->query($sql);
            $this->db->bind(1, $id);
            return $this->db->fetch();
        } catch (\Exception $e) {
            error_log("Error fetching book: " . $e->getMessage());
            throw new \Exception("Failed to fetch book");
        }
    }

    public function getAll($filters = []) {
        try {
            $sql = "SELECT * FROM books WHERE 1=1";
            $params = [];

            if (!empty($filters['title'])) {
                $sql .= " AND title LIKE ?";
                $params[] = "%{$filters['title']}%";
            }
            if (!empty($filters['author'])) {
                $sql .= " AND author LIKE ?";
                $params[] = "%{$filters['author']}%";
            }
            if (!empty($filters['category'])) {
                $sql .= " AND category = ?";
                $params[] = $filters['category'];
            }
            if (isset($filters['available'])) {
                $sql .= " AND available > 0";
            }

            $sql .= " ORDER BY title ASC";

            $this->db->query($sql);
            foreach ($params as $index => $param) {
                $this->db->bind($index + 1, $param);
            }

            return $this->db->fetchAll();
        } catch (\Exception $e) {
            error_log("Error fetching books: " . $e->getMessage());
            throw new \Exception("Failed to fetch books");
        }
    }

    private function validateBookData($data) {
        $required = ['title', 'author', 'isbn', 'category', 'quantity'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }

        if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
            throw new \Exception("Invalid quantity value");
        }

        // Validate ISBN format (basic validation)
        if (!preg_match('/^[0-9-]{10,13}$/', $data['isbn'])) {
            throw new \Exception("Invalid ISBN format");
        }
    }
} 