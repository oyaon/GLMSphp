<?php include ("header.php"); ?>
<?php include ("db-connect.php"); ?>
<div class="container">
	<h1 class="pt-2">Add Author</h1>
	<div class="row">
		<div class="col-6">
			<form method="POST" action="add-all-authors-asp.php">
				<div class="mb-3">
					<label class="form-label">Author Name</label>
					<input type="text" class="form-control" id="authorname" name="authorname">
				</div>
				<div class="mb-3">
					<label class="form-label">Author Description</label>
					<textarea class="form-control" id="author-description" name="authordescription" rows="5"></textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Book Type</label>
					<input type="text" class="form-control" id="book-type" name="booktype">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>

</div>

<?php include ("footer.php"); ?>
