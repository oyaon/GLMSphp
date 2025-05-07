<?php
require 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["bookname"])) {
        echo "Book Name required";
        die();
    } elseif (empty($_POST["bookauthor"])) {
        echo "Book Author required";
        die();
    } elseif (empty($_POST["bookcategory"])) {
        echo "Book Category required";
        die();
    } elseif (empty($_POST["bookquantity"])) {
        echo "Book Quantity required";
        die();
    } elseif (empty($_POST["bookprice"])) {
        echo "Book Price required";
        die();
    }
}

$book_name = $_POST['bookname'];
$book_author = $_POST['bookauthor'];
$book_category = $_POST['bookcategory'];
$book_quantity = $_POST['bookquantity'];
$book_price = $_POST['bookprice'];
$book_description = $_POST['bookdescription'];

if(isset($_FILES['pdf'])){
    $pdf = $_FILES['pdf']['name'];
    move_uploaded_file($_FILES['pdf']["tmp_name"], "../pdfs/$pdf");
}
else
    $pdf = "";

$sql = "INSERT INTO all_books
VALUES (0, '$book_name', '$book_author', '$book_category', '$book_description', '$pdf', '$book_quantity', '$book_price', "")";

if ($conn->query($sql)) {
    echo "New record created successfully";
    header("location:add-all-books.php");
} else {
    echo "Error" . $sql . "<br>" . $conn->error;
}
?>
