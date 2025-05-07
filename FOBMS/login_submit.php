<?php
	require('admin/db-connect.php');
	session_start();

	$email = $_POST['email'];
	$pass = $_POST['password'];

	$query = "SELECT * FROM users WHERE email='$email' AND password='$pass' ";
	$result = $conn->query($query);
	$row = $result->fetch_assoc();
	$user_type = $row['user_type'];

	if ($row != null) {
		$_SESSION['email'] = $email;
		$_SESSION['user_type'] = $user_type;

		if ($row['user_type'] == 1) {
			header("Location:index.php");
		}
		else if($row['user_type'] == 0){
			header("Location:admin/index.php");
		}
		else{
			echo 'Wrong email/password';
		}
	}
	else{
		echo 'please input correct email/password';
	}
?>