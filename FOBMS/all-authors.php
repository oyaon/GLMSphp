<?php include ("admin/db-connect.php"); ?>
<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>

<div class="container pb-4">
    <h3 class="text-center">Authors</h3>
    <div class="row">
        <?php
        $sql = "SELECT * FROM all_authors";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col-sm-4 mb-3 mb-sm-3">
            <div class="card p-3">
                <img src="images/books1.png" class="card-img-top rounded-circle" alt="...">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $row['author_name']; ?></h5>
                    <p class="card-text"><?php echo $row['author_description']; ?></p>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<?php include ("footer.php"); ?>