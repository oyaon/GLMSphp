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
	<h1 class="text-center mb-4"><u>Borrow history</u></h1>

	<table class="table table-hover table-stripped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Book name</th>
				<th scope="col">Issued date</th>
				<th scope="col">Return date</th>
				<th scope="col">Remainnig days</th>
				<th scope="col">Fine</th>
				<th scope="col">Status</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$user_email = $_SESSION["email"];
			$q = "SELECT *, borrow_history.id as id, all_books.name FROM `borrow_history` INNER JOIN `all_books` ON borrow_history.book_id=all_books.id WHERE `user_email`='$user_email';";
			$result = $conn->query($q);
			$i = 0;
			while ($data = $result->fetch_array()): ?>
				<tr>
					<th scope="row"><?php echo ++$i; ?></th>

					<td><?php echo $data["name"]; ?></td>

					<td><?php echo $data["issue_date"]; ?></td>

					<td>
						<?php
						if(!empty($data["issue_date"])){
							$date = strtotime($data["issue_date"]);
							$date = strtotime("+7days", $date);
							echo date("Y-m-d", $date);
						}
						?>
					</td>

					<td>
						<?php
						$rem_days = 0;
						if(!empty($data["issue_date"])){
							$rem_days = $date - strtotime(date("Y-m-d"));
							$rem_days = round($rem_days/86400);
							echo ($rem_days>0) ? $rem_days : 0;
							echo " days";
						}
						?>
					</td>

					<td>
						<?php
						if($data["fine"] == 0)
							echo ($rem_days<0) ? (abs($rem_days)+1)*2.5 : 0;
						else
							echo $data["fine"];
						?> Tk
					</td>

					<td>
						<?php echo $data["status"]; ?>
					</td>

					<td>
						<?php if($data["status"] == "Declined"): ?>
							<a href="actions.php?a=reissue&t=<?php echo $data["id"]; ?>" class="btn btn-success">Re-issue</a>
						<?php endif; ?>

						<?php if($data["status"] == "Declined" || $data["status"] == "Requested"): ?>
							<a href="actions.php?a=delete&t=<?php echo $data["id"]; ?>&book_id=<?php echo $data["book_id"]; ?>" class="btn btn-outline-danger">Delete request</a>
						<?php endif; ?>
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