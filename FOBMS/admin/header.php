<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Book Storage</title>
	<link rel="stylesheet" href="../css/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<link rel="stylesheet" href="../css/bootstrap-5.3.3/css/bootstrap.css">
	<link rel="stylesheet" href="../css/font-awsome/css/all.css">
</head>

<?php
	session_start();
	// var_dump($_SESSION['email']); die();

	if(!isset($_SESSION['email'])){
		header("Location:../login_page.php");
		exit();
	}

?>

<body>

	<!-- Sidebar added -->
	<?php include ("sidebar.php"); ?>