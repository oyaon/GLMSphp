<?php
if(isset($_GET)){
	require_once("db-connect.php");
	extract($_GET);

	if($a=="entry_paid"){
		$date = date("Y-m-d");
		if($conn->query("UPDATE `users` SET `entry_fee_stat`=1, `entry_fee_date`='$date' WHERE `email`='$t';")){ ?>
			<script type="text/javascript">
				alert("Response accepted");
				window.history.go(-1);
			</script>
		<?php }
	}
}
else{
	exit("Not accessable!");
}
?>