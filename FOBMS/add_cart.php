<?php
	if(isset($_GET['t'])){
		require_once("./admin/db-connect.php");
		session_start();

		$book_id = $_GET['t'];
		$user_email = $_SESSION['email'];
		$date = date("Y-m-d");
		$result = $conn->query("INSERT INTO `cart` VALUES(0, '$user_email', '$book_id', '$date', 0)");
		if(!$result){
			echo "Something went wrong. Please try again.";
		}
		else{
			$quantity = (int)$_GET['q'] - 1;
			$result = $conn->query("UPDATE `all_books` SET `quantity`=$quantity WHERE `id`=$book_id;");
			if(!$result){
				echo "Something went wrong. Please try again.";
			}
			else{ ?>
				<script type="text/javascript">
					alert("Added to cart successfully");
					window.location.assign("all-books.php");
				</script>
			<?php }
		}
	}
	else{ ?>
		<script type="text/javascript">
			window.history.go(-1);
		</script>
	<?php }
?>