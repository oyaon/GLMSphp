<?php include("admin/db-connect.php"); ?>
<?php include("header.php"); ?>
<?php include("top-navbar.php"); ?>

<!-- Search books -->
<div class="container py-4">
	<div class="row">
		<div class="col-sm-9 col-md-2 mb-3 mb-sm-3">
			<h3>Search Books</h3>
		</div>
		<div class="col-sm-3 col-md-9 mb-3 mb-sm-3">
			<div class="row">
				<form action="" method="POST">
					<div class="col-sm-3 col-md-6 mb-3 mb-sm-3">
						<div class="input-group mb-3">
							<input type="text" class="form-control" placeholder="Search Books" name="s-book-name">
							<button type="submit" class="btn btn-primary">Search</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-5">
			<hr class="border border-primary border-3 opacity-100 w-100">
		</div>
	</div>

	<!--  -->

	<!-- Books -->
	<div class="row">
		<?php
		if (isset($_POST['s-book-name'])) {
			$s_book_name = $_POST['s-book-name'];
			$sql = "SELECT * FROM `all_books` WHERE name LIKE '%$s_book_name%'";
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
		?>
				<div class="col-sm-3 mb-3 mb-sm-3">
					<div class="card">
						<img src="images/books1.png" class="card-img-top" alt="...">
						<div class="card-body">
							<h5 class="card-title"><?php echo $row['name']; ?></h5>
							<p class="card-text">Author: <?php echo $row['author']; ?></p>
							<p class="card-text">Quantity: <?php echo $row['quantity']; ?></p>
							<p class="card-text">Description: <?php echo $row['description']; ?></p>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">An item</li>
						</ul>
						<div class="card-body">
							<a href="#" class="btn btn-primary">View Details</a>
							<a href="#" class="btn btn-primary">Add to Cart</a>
						</div>
					</div>
				</div>
		<?php
			}
		} else {
			echo "<p>No search results found.</p>";
		}
		?>

	</div>
</div>

<!--  -->

<?php include("footer.php"); ?>