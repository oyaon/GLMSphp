<?php
require_once("db-connect.php");

if(isset($_GET["a"])){
	extract($_GET);

	if($a == "issue"){
		$date = date("Y-m-d");
		$query = $conn->query("UPDATE `borrow_history` SET `status`='Issued', `issue_date`='$date' WHERE `id`=$t ;");
		if($query){ ?>
			<script type="text/javascript">
				alert("Issued successfully");
				window.history.go(-1);
			</script>
		<?php }
	}
	else if($a == "decline"){
		$query = $conn->query("UPDATE `borrow_history` SET `status`='Declined' WHERE `id`=$t ;");
		if($query){ ?>
			<script type="text/javascript">
				alert("Declined successfully");
				window.history.go(-1);
			</script>
		<?php }
	}
	else if($a == "return"){
		$query = $conn->query("UPDATE `borrow_history` SET `status`='Returned', `fine`=$fine WHERE `id`=$t ;");
		if($query){
			$query = $conn->query("UPDATE `all_books` SET `quantity`=`quantity`+1 WHERE `id`=$b_id ;");
			if($query){ ?>
				<script type="text/javascript">
					alert("Status updated successfully");
					window.history.go(-1);
				</script>
			<?php }
		}
	}
}
else{
	echo "Bad request. Error: 101";
	exit();
}
?>