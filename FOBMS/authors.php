<?php require_once("./admin/db-connect.php"); ?>

<div class="container pb-4">
    <h3 class="text-center">Authors</h3>
    <div class="row">
        <?php
        $sql = "SELECT * FROM all_authors LIMIT 8"; // Limit the query to fetch only the first 8 records
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col-sm-3 mb-3 mb-sm-3"> <!-- Adjust the column size to fit 4 authors per row -->
            <div class="card p-3">
                <img src="images/books1.png" class="card-img-top rounded-circle" alt="...">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $row['author_name']; ?></h5>
                    <p class="card-text"><?php echo substr($row['author_description'], 0, 20); ?></p> <!-- Limit the description to 20 characters -->
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <p class="text-center"><a href="all-authors.php" class="btn btn-primary">View All Authors</a></p>
</div>
