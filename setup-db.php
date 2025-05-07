<?php
require_once 'config/config.php';

try {
    // Create connection without database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Read and execute schema file
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    if ($schema === false) {
        throw new Exception("Could not read schema file");
    }
    
    // Split and execute each SQL statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            if (!$conn->query($statement)) {
                throw new Exception("Error executing statement: " . $conn->error);
            }
        }
    }
    
    echo "Database setup completed successfully!\n";
    echo "Default admin credentials:\n";
    echo "Email: admin@example.com\n";
    echo "Password: admin123\n";
    
} catch (Exception $e) {
    die("Setup failed: " . $e->getMessage() . "\n");
} finally {
    if (isset($conn)) {
        $conn->close();
    }
} 