<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../config/config.php';

function executeSqlStatement($conn, $statement, $index) {
    echo "Executing statement " . ($index + 1) . "...\n";
    echo "Statement: " . $statement . "\n";
    
    if (!$conn->query($statement)) {
        throw new Exception(sprintf(
            "Error executing statement %d: %s\nStatement: %s",
            $index + 1,
            $conn->error,
            $statement
        ));
    }
    echo "Statement " . ($index + 1) . " executed successfully.\n\n";
}

try {
    // Check if mysqli extension is loaded
    if (!extension_loaded('mysqli')) {
        throw new Exception("The mysqli extension is not loaded. Please enable it in your PHP configuration.");
    }

    // Create connection without database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error . "\n\n";
        $error_message .= "Please ensure:\n";
        $error_message .= "1. MySQL is installed and running\n";
        $error_message .= "2. The credentials in config.php are correct\n";
        $error_message .= "3. MySQL is accessible at " . DB_HOST . "\n";
        $error_message .= "4. The user '" . DB_USER . "' has the necessary permissions\n";
        
        throw new Exception($error_message);
    }

    echo "Connected to MySQL server successfully.\n\n";

    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if (!$conn->query($sql)) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    echo "Database '" . DB_NAME . "' created or already exists.\n\n";

    // Select the database
    if (!$conn->select_db(DB_NAME)) {
        throw new Exception("Error selecting database: " . $conn->error);
    }
    echo "Selected database '" . DB_NAME . "'.\n\n";

    // Drop all tables if they exist
    $tables = ['borrowings', 'special_offers', 'books', 'users'];
    foreach ($tables as $table) {
        $sql = "DROP TABLE IF EXISTS " . $table;
        if (!$conn->query($sql)) {
            throw new Exception("Error dropping table " . $table . ": " . $conn->error);
        }
        echo "Dropped table '" . $table . "' if it existed.\n";
    }
    echo "\n";

    // Read and execute schema.sql
    $schema_file = __DIR__ . '/schema.sql';
    if (!file_exists($schema_file)) {
        throw new Exception("Schema file not found: " . $schema_file);
    }

    $sql = file_get_contents($schema_file);
    if ($sql === false) {
        throw new Exception("Error reading schema file: " . $schema_file);
    }
    
    // Remove CREATE DATABASE and USE statements
    $sql = preg_replace('/CREATE DATABASE.*?;/i', '', $sql);
    $sql = preg_replace('/USE.*?;/i', '', $sql);
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    echo "Executing schema statements...\n\n";
    foreach ($statements as $index => $statement) {
        if (!empty($statement)) {
            executeSqlStatement($conn, $statement, $index);
        }
    }

    echo "Database schema initialized successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    error_log("Database initialization error: " . $e->getMessage());
    exit(1);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
} 