<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="col-9">
    <h1>Edit Book</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                $id = $_GET["edit-id"];
                $sql = "SELECT * FROM `all_books` WHERE id = $id";
                $data = $conn->query($sql);
                $row = mysqli_fetch_array($data);
                ?>

                <form action="add-all-books-up.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name='id' value="<?php echo $row['id']; ?>">
                        <label for="bookname" class="form-label">Book Name</label>
                        <input type="text" class="form-control" id="bookname" name="bookname" value="<?php echo $row['name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="bookauthor" class="form-label">Book Author</label>
                        <input type="text" class="form-control" id="bookauthor" name="bookauthor" value="<?php echo $row['author']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="bookcategory" class="form-label">Book Category</label>
                        <input type="text" class="form-control" id="bookcategory" name="bookcategory" value="<?php echo $row['category']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="bookdescription" class="form-label">Book Description</label>
                        <textarea class="form-control" id="bookdescription" name="bookdescription" rows="5"><?php echo $row['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bookquantity" class="form-label">Book Quantity</label>
                        <input type="number" class="form-control" id="bookquantity" name="bookquantity" value="<?php echo $row['quantity']; ?>" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="bookprice" class="form-label">Book Price</label>
                        <input type="number" class="form-control" id="bookprice" name="bookprice" value="<?php echo $row['price']; ?>" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pdf">Book pdf</label>
                        <input type="file" name="pdf" id="pdf" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
