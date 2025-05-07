<?php include 'header.php'; ?>
<?php require 'db-connect.php'; ?>

<?php
$idErr = $nameErr = $descriptionErr = $typeErr = "";
$id = $name = $description = $type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["id"])) {
        $idErr = "ID is required";
    } else {
        $id = test_input($_POST["id"]);
    }

    if (empty($_POST["authorname"])) {
        $nameErr = "Author Name is required";
    } else {
        $name = test_input($_POST["authorname"]);
    }

    if (empty($_POST["authordescription"])) {
        $descriptionErr = "Author Description is required";
    } else {
        $description = test_input($_POST["authordescription"]);
    }

    if (empty($_POST["booktype"])) {
        $typeErr = "Book Type is required";
    } else {
        $type = test_input($_POST["booktype"]);
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!empty($id) && !empty($name) && !empty($description) && !empty($type)) {
    $sql = "UPDATE `all_authors` SET author_name='$name', author_description='$description', book_type='$type' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Author record updated successfully";
        header("location: add-all-authors.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<?php include 'footer.php'; ?>
