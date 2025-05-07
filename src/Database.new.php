<?php
namespace FOBMS;

class Database {
    private static $instance = null;
    private $conn;
    private $stmt;
    private $params = [];
    private $types = '';

    private function __construct() {
        try {
            $this->conn = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($this->conn->connect_error) {
                throw new \Exception("Database connection failed: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8mb4");
        } catch (\Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function beginTransaction() {
        $this->conn->begin_transaction();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function rollback() {
        $this->conn->rollback();
    }

    public function query($sql) {
        if ($this->stmt) {
            $this->stmt->close();
        }
        $this->stmt = $this->conn->prepare($sql);
        if (!$this->stmt) {
            throw new \Exception("Query preparation failed: " . $this->conn->error . "\nQuery: " . $sql);
        }
        $this->params = [];
        $this->types = '';
        return $this;
    }

    public function bind($param, $value) {
        if (is_int($value)) {
            $this->types .= 'i';
        } elseif (is_float($value)) {
            $this->types .= 'd';
        } elseif (is_bool($value)) {
            $this->types .= 'i';
            $value = $value ? 1 : 0;
        } elseif (is_null($value)) {
            $this->types .= 's';
            $value = null;
        } else {
            $this->types .= 's';
        }
        $this->params[] = $value;
        return $this;
    }

    public function execute() {
        if (!empty($this->params)) {
            $params = array_merge([$this->types], $this->params);
            $refs = [];
            foreach ($params as $key => $value) {
                $refs[$key] = &$params[$key];
            }
            call_user_func_array([$this->stmt, 'bind_param'], $refs);
        }
        $result = $this->stmt->execute();
        if (!$result) {
            throw new \Exception("Query execution failed: " . $this->stmt->error);
        }
        return $result;
    }

    public function fetch() {
        $result = $this->stmt->get_result();
        if (!$result) {
            throw new \Exception("Failed to get result: " . $this->stmt->error);
        }
        $row = $result->fetch_assoc();
        $result->free();
        return $row;
    }

    public function fetchAll() {
        $result = $this->stmt->get_result();
        if (!$result) {
            throw new \Exception("Failed to get result: " . $this->stmt->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $rows;
    }

    public function lastInsertId() {
        return $this->conn->insert_id;
    }

    public function __destruct() {
        if ($this->stmt) {
            $this->stmt->close();
        }
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        
        $this->query($sql);
        foreach ($data as $value) {
            $this->bind(null, $value);
        }
        return $this->execute();
    }

    public function update($table, $data, $where, $whereParams = []) {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE $table SET $set WHERE $where";
        
        $this->query($sql);
        foreach (array_merge(array_values($data), $whereParams) as $value) {
            $this->bind(null, $value);
        }
        return $this->execute();
    }

    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql);
        foreach ($params as $value) {
            $this->bind(null, $value);
        }
        return $this->execute();
    }

    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }
} 