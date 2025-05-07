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
	<h1 class="text-center mb-4"><u>Order history</u></h1>

	<table class="table table-hover table-stripped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Date</th>
				<th scope="col">Amount</th>
				<th scope="col">Transiction ID</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$user_email = $_SESSION["email"];
			$q = "SELECT * FROM `payments` WHERE `email`='$user_email';";
			$result = $conn->query($q);
			$i = 0;
			while ($data = $result->fetch_array()): ?>
				<tr>
					<th scope="row"><?php echo ++$i; ?></th>

					<td><?php echo $data["date"]; ?></td>

					<td><?php echo $data["amount"]; ?> Tk</td>

					<td><?php echo $data["trans_id"]; ?></td>

					<td>
						<button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-primary" onclick="document.getElementById('bookList').setAttribute('src','book_list.php?t=<?php echo $data["book_ids"]; ?>')">View book list</button>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>

<!-- book list modal -->
<div class="modal modal-lg fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<iframe id="bookList" style="width: 100%; height: 100%;"></iframe>
			</div>
		</div>
	</div>
</div>
<!-- book list modal -->

<?php include ("footer.php"); ?>