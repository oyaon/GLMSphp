<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="container">
    <h1 class="pt-2">All Authors</h1>
    <div class="row">
        <div class="col-lg-9"></div>
        <div class="col-lg-3">
            <a class="btn btn-primary mb-3" href="add-all-authors-afp.php" role="button">Add New Author</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `all_authors` ORDER BY `id` ASC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['author_name']; ?></td>
                                    <td><?php echo substr($row['author_description'], 0, 50) . "..."; ?></td>
                                    <td><?php echo $row['book_type']; ?></td>
                                    <td>
                                        <a href="add-all-authors-efp.php?edit-id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a href="add-all-authors-dp.php?del-id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='5'>No authors found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
