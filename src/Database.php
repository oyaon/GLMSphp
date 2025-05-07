<?php
namespace FOBMS;

class Database {
    private static $instance = null;
    private $pdo;
    private $stmt;
    private $inTransaction = false;
    private $paramCount = 0;

    private function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->pdo = new \PDO($dsn, DB_USER, DB_PASS, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (\PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function beginTransaction() {
        if ($this->inTransaction) {
            throw new \Exception("Transaction already in progress");
        }
        $this->pdo->beginTransaction();
        $this->inTransaction = true;
    }

    public function commit() {
        if (!$this->inTransaction) {
            throw new \Exception("No transaction in progress");
        }
        $this->pdo->commit();
        $this->inTransaction = false;
    }

    public function rollback() {
        if (!$this->inTransaction) {
            throw new \Exception("No transaction in progress");
        }
        $this->pdo->rollBack();
        $this->inTransaction = false;
    }

    public function query($sql) {
        try {
            $this->stmt = $this->pdo->prepare($sql);
            $this->paramCount = 0;
            return $this;
        } catch (\PDOException $e) {
            error_log("Query preparation failed: " . $e->getMessage() . "\nQuery: " . $sql);
            throw new \Exception("Query preparation failed: " . $e->getMessage());
        }
    }

    public function bind($param, $value) {
        try {
            $type = \PDO::PARAM_STR;
            if (is_int($value)) {
                $type = \PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = \PDO::PARAM_BOOL;
            } elseif (is_null($value)) {
                $type = \PDO::PARAM_NULL;
            }
            $this->paramCount++;
            $this->stmt->bindValue($param === null ? $this->paramCount : $param, $value, $type);
            return $this;
        } catch (\PDOException $e) {
            error_log("Parameter binding failed: " . $e->getMessage());
            throw new \Exception("Parameter binding failed: " . $e->getMessage());
        }
    }

    public function execute() {
        try {
            return $this->stmt->execute();
        } catch (\PDOException $e) {
            error_log("Query execution failed: " . $e->getMessage());
            throw new \Exception("Query execution failed: " . $e->getMessage());
        }
    }

    public function fetch() {
        try {
            $this->execute();
            return $this->stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Fetch failed: " . $e->getMessage());
            throw new \Exception("Fetch failed: " . $e->getMessage());
        }
    }

    public function fetchAll() {
        try {
            $this->execute();
            return $this->stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("FetchAll failed: " . $e->getMessage());
            throw new \Exception("FetchAll failed: " . $e->getMessage());
        }
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
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
        return substr($this->pdo->quote($value), 1, -1);
    }
} 