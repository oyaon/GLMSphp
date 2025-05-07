<?php include ("admin/db-connect.php"); ?>
<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>

<div class="container py-4">
	<div class="row">
		<div class="col-sm-9 mb-3 mb-sm-3">
			<h3>All Books</h3>
		</div>

		<div class="col-sm-3 mb-3 mb-sm-3">
			<div class="row g-3 align-items-center">
				<div class="col-auto">
					<form method="GET" action="">
						<div class="form-group">
							<input class="form-control" type="text" name="s" required placeholder="Search by book name">

							<button class="btn btn-success mt-1" type="submit">Search</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php if(isset($_GET["s"])): ?>
		<div class="row">
			<div class="col-12">
				<h2>Showing result for: "<?php echo $_GET["s"]; ?>"</h2>
				<a class="btn btn-info" href="all-books.php">Clear filter</a>
			</div>
		</div>
	<?php endif; ?>

	<div class="row justify-content-center mb-5">
		<div class="col-5">
			<hr class="border border-primary border-3 opacity-100 w-100">
		</div>
	</div>

	<!-- Books -->
	<div class="row">
		<?php
		$sql = "SELECT * FROM `all_books` WHERE NOT `quantity`=0 ";

		if(isset($_GET["s"]))
			$sql .= "AND `name` LIKE ('%".$_GET["s"]."%')";
		$sql .= ";";

		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()):
			?>
			<div class="col-sm-3 mb-3 mb-sm-3">
				<div class="card">
					<img src="images/books1.png" class="card-img-top" alt="...">

					<div class="card-body">
						<h5 class="card-title"><?php echo $row['name']; ?></h5>
						<p class="card-text"><u>Author:</u> <?php echo $row['author']; ?></p>

						<p class="card-text">
							<u>Description:</u>
							<br>
							<?php echo $row['description']; ?>
						</p>

						<p class="card-text"><u>Quantity:</u> <b><?php echo $row['quantity']; ?></b></p>
					</div>
					
					<div class="card-body d-flex justify-content-center">
						<a href="book-details.php?t=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</div>

<?php include ("footer.php"); ?>