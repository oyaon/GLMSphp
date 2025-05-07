<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>
<?php require_once("./admin/db-connect.php"); ?>

<?php if(!isset($_SESSION["email"])): ?>
	<script type="text/javascript">
		alert("Login first!");
		window.location.assign("login_page.php");
	</script>
	<?php exit(); ?>
<?php endif; ?>

<style type="text/css">
	.table{
		width: 700px;
		margin: 2.5rem auto;
	}

	td,th{
		text-align: center;
	}
</style>

<div class="container my-5">
	<h1 class="text-center mb-4"><u>Your Orders</u></h1>

	<table class="table table-hover table-stripped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Date</th>
				<th scope="col">Image</th>
				<th scope="col">Book name</th>
				<th scope="col">Price</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$user_email = $_SESSION["email"];
			$q = "SELECT all_books.id as book_id, all_books.name, all_books.price, all_books.quantity as book_quantity, cart.book_id, cart.id as cart_id, cart.date, cart.user_email, cart.status FROM `all_books` INNER JOIN `cart` ON all_books.id=cart.book_id AND cart.user_email='$user_email' AND cart.status=0;";
			$result = $conn->query($q);
			$total_price = 0;
			$i = 0;
			$ids = "";
			while ($data = $result->fetch_array()): ?>
				<?php $ids .= $data['book_id'].","; ?>
				<tr>
					<th scope="row"><?php echo ++$i; ?></th>

					<td><?php echo $data["date"]; ?></td>

					<td>
						<img width="96px" src="./images/books1.png" alt="book_img">
					</td>

					<td><?php echo $data["name"]; ?></td>

					<td><?php echo $data["price"]; ?>/=</td>

					<td>
						<a class="btn btn-outline-danger" href="delete_cart.php?t=<?php echo $data["cart_id"]; ?>&b=<?php echo $data["book_id"]; ?>&q=<?php echo $data["book_quantity"]; ?>">Remove</a>
					</td>
				</tr>
				<?php $total_price += $data["price"]; ?>
			<?php endwhile; ?>
		</tbody>
	</table>

	<!-- Calculation -->
	<?php if($result->num_rows > 0): ?>
		<!-- divider -->
		<div class="row">
			<div class="col-8"></div>
			<div class="col-sm-12 col-md-4">
				<hr class="border border-danger border-3 opacity-50 w-100">
			</div>
		</div>
		<!-- divider -->
		
		<div class="row mb-2">
			<div class="col-sm-12 col-md-8"></div>
			<div class="col-sm-12 col-md-4">
				<div class="row">
					<div class="col-6 fs-4">Total</div>
					<div class="col-6 fs-4"><?php echo $total_price; ?> TK</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-8"></div>
			<div class="col-sm-12 col-md-4">
				<div class="row">
					<div class="col-12 text-center fs-4">
						<form method="POST" action="payment.php">
							<input type="hidden" name="ids" value="<?php echo $ids; ?>">
							<input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

							<button type="submit" class="btn bg-danger-subtle mt-2 rounded-pill">Proceed to Checkout</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php include ("footer.php"); ?>