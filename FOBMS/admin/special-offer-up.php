<?php include 'header.php'; ?>
<?php require 'db-connect.php'; ?>

<?php
$idErr = $headerTopErr = $headerErr = $headerBottomErr = "";
$id = $headerTop = $header = $headerBottom = "";

if (empty($_POST["id"])) {
	$idErr = "ID is required";
} else {
	$id = test_input($_POST["id"]);
}

if (empty($_POST["headerTop"])) {
	$headerTopErr = "Header Top is required";
} else {
	$headerTop = test_input($_POST["headerTop"]);
}

if (empty($_POST["header"])) {
	$headerErr = "Header is required";
} else {
	$header = test_input($_POST["header"]);
}

if (empty($_POST["headerBottom"])) {
	$headerBottomErr = "Header Bottom is required";
} else {
	$headerBottom = test_input($_POST["headerBottom"]);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!empty($id) && !empty($headerTop) && !empty($header) && !empty($headerBottom)) {
    $sql = "UPDATE `special_offer` SET header_top='$headerTop', header='$header', header_bottom='$headerBottom' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Special offer record updated successfully";
        header("location:special-offer.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<?php include 'footer.php'; ?>
