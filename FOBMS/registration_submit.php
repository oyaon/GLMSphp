<?php 
session_start();
require('admin/db-connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        echo "All fields are required.";
        die();
    }

    // Sanitize input data
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        die();
    }

    // Check if email is already registered
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = $conn->query($check_query);
    if ($check_result->num_rows > 0) {
        echo "Email already registered.";
        die();
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (first_name, last_name, user_name, email, password, ) VALUES ('$firstname', '$lastname', '$username', '$email', '$password')";
    if ($conn->query($sql)) {
        echo "Registration Successful.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>