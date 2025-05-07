<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="container">
    <h1 class="pt-2">All donations</h1>
    
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Donator</th>
                            <th>Total donation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT `donator` FROM `all_books` WHERE NOT `donator`='';";
                        $result = $conn->query($sql);
                        $i = 0;
                        if ($result->num_rows > 0) {
                            $data = [];
                            $donators = [];
                            while ($row = $result->fetch_array()){
                                $data[$row["donator"]] = 0;
                                array_push($donators, $row["donator"]);
                            }

                            foreach ($donators as $val) {
                                $data[$val]++;
                            }

                            foreach($data as $k => $v){
                                ?>
                                <tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td><?php echo $k; ?></td>
                                    <td><?php echo $v; ?></td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='5'>No donations done yet</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>