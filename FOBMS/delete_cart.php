<?php
	require_once("admin/db-connect.php");
	$id = $_GET['t'];
	if($conn->query("DELETE FROM `cart` WHERE `id`=$id")){
		$quantity = (int)$_GET['q'] + 1;
		$book_id = $_GET['b'];
		if($conn->query("UPDATE `all_books` SET `quantity`=$quantity WHERE `id`=$book_id")){
			header("Location: cart-page.php");
		}
	}
?>