<?php include ("header.php"); ?>
<?php require_once("./admin/db-connect.php"); ?>

<style type="text/css">
	.table{
		width: 700px;
		margin: 2.5rem auto;
	}

	td,th{
		text-align: center;
	}
</style>

<table class="table table-hover table-stripped">
	<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col">Image</th>
			<th scope="col">Book name</th>
			<th scope="col">Price</th>
			<th scope="col">Quantity</th>
		</tr>
	</thead>

	<tbody>
		<?php
		$user_email = $_SESSION["email"];
		$ids = $_GET['t'];
		$q = "SELECT id, name, price FROM `all_books` WHERE `id` IN ($ids);";
		$result = $conn->query($q);
		$i = 0;

		$ids = explode(',', $ids);
		$freq = [];
		foreach($ids as $k){
			$freq[$k] = 0;
		}
		foreach($ids as $k){
			$freq[$k]++;
		}

		while ($data = $result->fetch_array()): ?>
			<tr>
				<th scope="row"><?php echo ++$i; ?></th>

				<td>
					<img width="96px" src="./images/books1.png" alt="book_img">
				</td>

				<td><?php echo $data["name"]; ?></td>

				<td><?php echo $data["price"]; ?>/=</td>

				<td><?php echo $freq[$data["id"]]; ?></td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>