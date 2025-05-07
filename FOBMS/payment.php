<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>
<?php require_once("./admin/db-connect.php"); ?>

<?php
extract($_POST);
$date = date("Y-m-d");
$email = $_SESSION['email'];

if(!isset($_POST["submit"])){
	$ids = substr($ids, 0, -1);

	if($total_price==0){
		if($conn->query("INSERT INTO `payments` VALUES(0, '$email', '$ids', $total_price, '$date', '');")){
			if($conn->query("UPDATE `cart` SET `status`=1 WHERE `user_email`='$email' AND `book_id` IN ($ids);")){
				?>
				<script type="text/javascript">
					alert("Payment Successful");
					window.location.assign("./order_history.php");
				</script>
				<?php
			}
		}
	}
	else{ ?>
		<div class="row my-4">
			<div class="container">
				<div class="col-12">
					<h1 class="text-center my-4">Payment</h1>

					<form action="" method="POST" class="mx-auto p-4" style="width: 550px; border: 1px solid gray; border-radius: 23px;" enctype="multipart/form-data">
						<div class="form-group mb-2">
							<h1>Total amount: <?php echo $total_price; ?> Tk</h1>
							
							<p>
								Send the above amount in the following accounts and submit the transiction id
							</p>

							<ul>
								<li><u>Bkash:</u> 0178171685</li>
								<li><u>Nagad:</u> 0178171685</li>
								<li><u>Rocket:</u> 0178171685</li>
							</ul>
						</div>

						<div class="form-group mb-2">
							<label class="form-label" for="trans_id">Transiction ID</label>
							<input type="text" name="trans_id" id="trans_id" required class="form-control">
						</div>

						<input type="hidden" name="ids" value="<?php echo $ids; ?>">
						<input type="hidden" name="amount" value="<?php echo $total_price; ?>">

						<div class="form-group mt-4 d-flex justify-content-center">
							<button name="submit" type="submit" class="btn btn-success">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php }
}
else{
	if($conn->query("INSERT INTO `payments` VALUES(0, '$email', '$ids', $amount, '$date', '$trans_id');")){
		if($conn->query("UPDATE `cart` SET `status`=1 WHERE `user_email`='$email' AND `book_id` IN ($ids);")){
			?>
			<script type="text/javascript">
				alert("Payment Successful!!");
				window.location.assign("./order_history.php");
			</script>
			<?php
		}
	}
}
?>

<?php include ("footer.php"); ?>