<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="container">
    <h1 class="pt-2">Special Offers</h1>
		
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Header Top</th>
                            <th>Header</th>
                            <th>Header Bottom</th>
                            <th>Action</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM special_offer ORDER BY id ASC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row['header_top']; ?></td>
                                    <td><?php echo $row['header']; ?></td>
                                    <td><?php echo $row['header_bottom']; ?></td>
                                    <td>
                                        <a class="btn btn-primary" href="special-offer-efp.php?edit-id=<?php echo $row['id']; ?>" role="button">Update Offer</a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='5'>No special offers found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
