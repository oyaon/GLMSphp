<?php include 'header.php'; ?>
<?php include 'db-connect.php'; ?>

<div class="container">
    <h1 class="pt-2">Summary report</h1>
    
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Duration</th>
                            <th>Entry fee</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $entry_n_fines = [];
                            $x = [];

                            // fetching entry fee data
                            $sql = "SELECT `entry_fee_stat`, `entry_fee_date` FROM `users` WHERE `entry_fee_stat`=1 ORDER BY `entry_fee_date` ASC;";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_array()){
                                $date = explode("-", $data["entry_fee_date"]);
                                $time = date("F", mktime(0, 0, 0, (int)$date[1])).", $date[0]";
                                $entry_n_fines[$time] = [
                                    "entry" => 0,
                                    "fine" => 0
                                ];
                                array_push($x, $data);
                            }
                            
                            foreach ($x as $k) {
                                $date = explode("-", $k["entry_fee_date"]);
                                $time = date("F", mktime(0, 0, 0, (int)$date[1])).", $date[0]";
                                $entry_n_fines[$time]["entry"]++;
                            }
                            unset($x);
                            $x = [];

                            // fetching fine data
                            $sql = "SELECT `issue_date`, `fine` FROM `borrow_history` WHERE NOT `fine`=0 ORDER BY `issue_date` ASC;";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_array())
                                array_push($x, $data);

                            foreach ($x as $k) {
                                $date = explode("-", $k["issue_date"]);
                                $time = date("F", mktime(0, 0, 0, (int)$date[1])).", $date[0]";
                                $entry_n_fines[$time]["fine"] += $k["fine"];
                            }
                            ?>

                            <?php $total_entries=0;$total_fine=0;$i=0; ?>
                            <?php foreach($entry_n_fines as $key => $val): ?>
                                <tr>
                                    <td><?php echo ++$i; ?></td>

                                    <td><?php echo $key; ?></td>

                                    <td><?php echo $val["entry"]*100; ?> Tk</td>

                                    <td><?php echo $val["fine"]; ?> Tk</td>

                                    <?php $total_entries+=$val["entry"]*100;$total_fine+=$val["fine"]; ?>
                                </tr>
                            <?php endforeach; ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td></td>
                            <td>Total</td>

                            <td><?php echo $total_entries; ?> Tk</td>

                            <td><?php echo $total_fine; ?> Tk</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>