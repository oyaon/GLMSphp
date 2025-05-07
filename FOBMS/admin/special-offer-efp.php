<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="col-9">
    <h1>Edit Special Offer</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                $id = $_GET["edit-id"];
                $sql = "SELECT * FROM `special_offer` WHERE id = $id";
                $data = $conn->query($sql);
                $row = mysqli_fetch_array($data);
                ?>

                <form action="special-offer-up.php" method="POST">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name='id' value="<?php echo $row['id']; ?>">
                        <label for="headerTop" class="form-label">Header Top</label>
                        <input type="text" class="form-control" id="headerTop" name="headerTop" value="<?php echo $row['header_top']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="header" class="form-label">Header</label>
                        <input type="text" class="form-control" id="header" name="header" value="<?php echo $row['header']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="headerBottom" class="form-label">Header Bottom</label>
                        <input type="text" class="form-control" id="headerBottom" name="headerBottom" value="<?php echo $row['header_bottom']; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
