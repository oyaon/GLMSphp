<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="col-9">
    <h1>Edit Author</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                $id = $_GET["edit-id"];
                $sql = "SELECT * FROM `all_authors` WHERE id = $id";
                $data = $conn->query($sql);
                $row = mysqli_fetch_array($data);
                ?>

                <form action="add-all-authors-up.php" method="POST">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name='id' value="<?php echo $row['id']; ?>">
                        <label for="authorname" class="form-label">Author Name</label>
                        <input type="text" class="form-control" id="authorname" name="authorname" value="<?php echo $row['author_name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="authordescription" class="form-label">Author Description</label>
                        <textarea class="form-control" id="authordescription" name="authordescription" rows="5"><?php echo $row['author_description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="booktype" class="form-label">Book Type</label>
                        <input type="text" class="form-control" id="booktype" name="booktype" value="<?php echo $row['book_type']; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>