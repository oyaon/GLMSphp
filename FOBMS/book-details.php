<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>
<?php require_once("./admin/db-connect.php"); ?>

<div class="container py-4">
	<h3>Book details</h3>

	<div class="card my-3 border-0">
		<div class="row g-0">
			<div class="col-md-6">
				<img src="images/books1.png" class="img-fluid rounded-start" alt="..." />
			</div>

			<div class="col-md-6 d-flex align-items-start">
				<?php
					$id = $_GET["t"];
					$result = $conn->query("SELECT * FROM `all_books` WHERE `id`=$id;");
					$data = $result->fetch_assoc();
				?>
				<div class="card-body">
					<h5 class="card-title fs-2"><?php echo $data["name"]; ?></h5>
					<p class="card-text fs-5">Quantity: <b><?php echo $data["quantity"]; ?></b></p>

					<p class="card-text fs-4">Description</p>
					<p class="card-text fs-6"><?php echo $data["description"]; ?></p>

					<div class="mb-3">
						<a href="borrow.php?t=<?php echo $data['id']; ?>&q=<?php echo $data['quantity']; ?>" class="btn btn-lg btn-primary">Borrow this book</a>

						<?php if($data["pdf"] != ""): ?>
							<a class="btn btn-outline-secondary btn-lg" href="./pdfs/<?php echo $data['pdf']; ?>" target="_blank">View pdf</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include ("footer.php"); ?>