<?php
require_once("./admin/db-connect.php");

if(isset($_GET["a"])){
	extract($_GET);

	if($a == "reissue"){
		$query = $conn->query("UPDATE `borrow_history` SET `status`='Requested' WHERE `id`=$t ;");
		if($query){ ?>
			<script type="text/javascript">
				alert("Re-issued successfully");
				window.history.go(-1);
			</script>
		<?php }
	}
	else if($a == "delete"){
		$query = $conn->query("DELETE FROM `borrow_history` WHERE `id`=$t ;");
		if($query){ 
			if($conn->query("UPDATE `all_books` SET `quantity`=`quantity`+1 WHERE `id`=$book_id ;")){ ?>
				<script type="text/javascript">
					alert("Request deleted successfully");
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