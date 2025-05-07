<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="container">
    <h1 class="pt-2">All Books</h1>
    <div class="row">
        <div class="col-lg-9"></div>
        <div class="col-lg-3">
            <a class="btn btn-primary mb-3" href="add-all-books-afp.php" role="button">Add New Book</a>
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
                            <th>Author</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Total quantity</th>
                            <th>Left quantity</th>
                            <th>Issued quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `all_books` ORDER BY `id` ASC";
                        $result = $conn->query($sql);
                        $data = [];
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $row["issued"] = 0;
                                array_push($data, $row);
                            }

                            $sql = "SELECT `book_id` FROM `borrow_history`;";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()){
                                for($i=0; $i<sizeof($data); $i++) {
                                    if($data[$i]["id"]==$row["book_id"]){
                                        $data[$i]["issued"]++;
                                        break;
                                    }
                                }
                            }

                            foreach($data as $row){
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['author']; ?></td>
                                    <td><?php echo $row['category']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['quantity']+$row['issued']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['issued']; ?></td>
                                    <td>
                                        <a href="add-all-books-efp.php?edit-id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>

                                        <a href="add-all-books-dp.php?del-id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>

                                        <a href="history.php?t=<?php echo $row['id']; ?>" class="btn btn-primary">See issuers list</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='8'>No books found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
