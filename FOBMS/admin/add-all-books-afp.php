<?php include ("header.php"); ?>
<?php include ("db-connect.php"); ?>

<div class="container">
	<h1 class="pt-2">Add Book</h1>
	<div class="row">
		<div class="col-6">
			<form method="POST" action="add-all-books-asp.php" enctype="multipart/form-data">
				<div class="mb-3">
					<label class="form-label">Book Name</label>
					<input type="text" class="form-control" id="bookname" name="bookname">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Author</label>
					<input type="text" class="form-control" id="book-author" name="bookauthor">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Category</label>
					<input type="text" class="form-control" id="book-category" name="bookcategory">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Description</label>
					<textarea class="form-control" id="book-description" name="bookdescription" rows="5"></textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Book Quantity</label>
					<input type="number" class="form-control" id="book-quantity" name="bookquantity" min="1">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Price</label>
					<input type="number" class="form-control" id="book-price" name="bookprice" min="1">
				</div>
				<div class="mb-3">
					<label class="form-label" for="pdf">Book pdf</label>
					<input type="file" name="pdf" id="pdf" class="form-control">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>

</div>

<?php include ("footer.php"); ?>