<?php
require 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["authorname"])) {
        echo "Author Name required";
        die();
    } elseif (empty($_POST["authordescription"])) {
        echo "Author Description required";
        die();
    } elseif (empty($_POST["booktype"])) {
        echo "Book Type required";
        die();
    }
}

$author_name = $_POST['authorname'];
$author_description = $_POST['authordescription'];
$book_type = $_POST['booktype'];

$sql = "INSERT INTO all_authors(author_name, author_description, book_type)
VALUES ('$author_name', '$author_description', '$book_type')";

if ($conn->query($sql)) {
    echo "New record created successfully";
    header("location:add-all-authors.php");
} else {
    echo "Error" . $sql . "<br>" . $conn->error;
}

?>
