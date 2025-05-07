<?php
if(isset($_GET['t'])){
	require_once("./admin/db-connect.php");
	session_start();

	$book_id = $_GET['t'];
	$user_email = $_SESSION['email'];
	$borrow_count = $conn->query("SELECT * FROM `borrow_history` WHERE `book_id`=$book_id AND `user_email`='$user_email' AND NOT `status`='Returned';");
	if(mysqli_num_rows($borrow_count)==1){ ?>
		<script type="text/javascript">
			alert("Already requested or issued");
			window.history.go(-1);
		</script>
	<?php }
	else{
		$issue_count = $conn->query("SELECT * FROM `borrow_history` WHERE `user_email`='$user_email' AND NOT `status`='Returned';");
		if(mysqli_num_rows($issue_count)==3){ ?>
			<script type="text/javascript">
				alert("Can not issue more than 3 books without returning");
				window.history.go(-1);
			</script>
		<?php }
		else{
			$result = $conn->query("INSERT INTO `borrow_history` VALUES(0, '$user_email', '$book_id', null, 0, 'Requested')");
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
						alert("Borrow request submitted successfully");
						window.location.assign("all-books.php");
					</script>
				<?php }
			}
		}
	}
}
else{ ?>
	<script type="text/javascript">
		window.history.go(-1);
	</script>
<?php }
?>