<?php
require 'db-connect.php';

$id = $_GET['del-id'];

$sql = "DELETE FROM `all_authors` WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
    header("location: add-all-authors.php");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
