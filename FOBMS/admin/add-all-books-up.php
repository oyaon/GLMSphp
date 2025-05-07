<?php include 'header.php'; ?>
<?php require 'db-connect.php'; ?>

<?php
$idErr = $nameErr = $authorErr = $categoryErr = $quantityErr = $priceErr = "";
$id = $name = $author = $category = $quantity = $price = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["id"])) {
        $idErr = "ID is required";
    } else {
        $id = test_input($_POST["id"]);
    }

    if (empty($_POST["bookname"])) {
        $nameErr = "Book Name is required";
    } else {
        $name = test_input($_POST["bookname"]);
    }

    if (empty($_POST["bookauthor"])) {
        $authorErr = "Book Author is required";
    } else {
        $author = test_input($_POST["bookauthor"]);
    }

    if (empty($_POST["bookcategory"])) {
        $categoryErr = "Book Category is required";
    } else {
        $category = test_input($_POST["bookcategory"]);
    }

    if (empty($_POST["bookquantity"])) {
        $quantityErr = "Book Quantity is required";
    } else {
        $quantity = test_input($_POST["bookquantity"]);
    }

    if (empty($_POST["bookprice"])) {
        $priceErr = "Book Price is required";
    } else {
        $price = test_input($_POST["bookprice"]);
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!empty($id) && !empty($name) && !empty($author) && !empty($category) && !empty($quantity) && !empty($price)) {
    $sql = "UPDATE `all_books` SET name='$name', author='$author', category='$category', quantity='$quantity', price='$price'";

    if(isset($_FILES['pdf']) && $_FILES['pdf']['name']!=""){
        $pdf = $_FILES['pdf']['name'];
        move_uploaded_file($_FILES['pdf']["tmp_name"], "../pdfs/$pdf");
        $sql .= ", `pdf`='$pdf'";
    }

    $sql .= " WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Book record updated successfully";
        header("location: add-all-books.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<?php include 'footer.php'; ?>
