<?php include ("admin/db-connect.php"); ?>

<!-- Special Offers -->
<div class="container pb-4">
    <h3 class="text-center">Special Offers</h3>
    <div class="row">
        <?php
        $sql = "SELECT * FROM special_offer";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { ?>
            <div class="col-sm-6 col-md-12">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-5 d-flex align-items-center">
                            <div class="card-body">
                                <p class="card-text fs-5"><?php echo $row['header_top']; ?></p>
                                <h5 class="card-title fs-4"><?php echo $row['header']; ?></h5>
                                <p class="card-text fs-6"><?php echo $row['header_bottom']; ?></p>
                                <a href="all-books.php" class="btn btn-primary">Browse Now</a>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <img src="images/home-content.png" class="img-fluid rounded-end" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

