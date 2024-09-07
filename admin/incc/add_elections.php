<?php
if (isset($_GET['added'])) {
    ?>
    
    <div class="alert alert-success my-3" role="alert">
        Election has been added successfully!
    </div>
    <?php
} else if (isset($_GET['delete_id'])) {
    mysqli_query($db, "DELETE  FROM elections WHERE id ='" . $_GET['delete_id'] . "'") or die(mysqli_error($db));
    ?>
        <div class="alert alert-danger my-3" role="alert">
            Election has been deleted successfully!
        </div>
    <?php
}
?>

<div class="row my-3">
    <div class="col-4">
        <h3 style="color: white;">Add New Election</h3>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="number" name="number_of_candidates" placeholder="Number Of Candidates" class="form-control"
                    required />
            </div>
            <div class="form-group">
                <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Staring Date"
                    class="form-control" required />
            </div>
            <div class="form-group">
                <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date"
                    class="form-control" required />
            </div>
            <input type="submit" value="Add Election" name="addElectionBtn" class="btn btn-success" />
        </form>
    </div>

    <div class="col-8">
        <h3 style="color: white;">Upcoming Elections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="color: white;">S.No</th>
                    <th scope="col" style="color: white;">Election Name</th>
                    <th scope="col" style="color: white;"> No. Of Candicates</th>
                    <th scope="col" style="color: white;">Starting Date</th>
                    <th scope="col" style="color: white;">Ending Date</th>
                    <th scope="col" style="color: white;">Status</th>
                    <th scope="col" style="color: white;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                $isAnyElectionAdded = mysqli_num_rows($fetchingData);

                if ($isAnyElectionAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id = $row['id'];
                        ?>
                        <tr>
                            <td style="color: white;"> <?php echo $sno++; ?> </td>
                            <td style="color: white;"> <?php echo $row['election_topic']; ?></td>
                            <td style="color: white;"> <?php echo $row['no_of_candidates']; ?></td>
                            <td style="color: white;"> <?php echo $row['starting_date']; ?></td>
                            <td style="color: white;"> <?php echo $row['ending_date']; ?></td>
                            <td style="color: white;"> <?php echo $row['status']; ?></td>
                            <td>
                                <a href="edit_election.php?election_id=<?php echo $row['id']; ?>"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <button class="btn btn-sm btn-danger"
                                    onclick="DeleteData(<?php echo $election_id; ?>)">Delete</button>
                            </td>

                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" style="color: white;">No any election added yet! </td>

                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (e_id) => {
        let c = confirm("Are you sure you want to delete it!!");
        if (c == true) {
            location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
        }
    }
</script>

<?php
if (isset($_POST['addElectionBtn'])) {
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $current_date = new DateTime($inserted_on);
    $start_date = new DateTime($starting_date);
    $end_date = new DateTime($ending_date);

    if ($current_date < $start_date) {
        $status = "Inactive";
    } elseif ($current_date >= $start_date && $current_date <= $end_date) {
        $status = "Active";
    } else {
        $status = "Expired";
    }

    mysqli_query($db, "INSERT INTO elections(election_topic, no_of_candidates, starting_date, ending_date, status, inserted_by, inserted_on) VALUES ('" . $election_topic . "', '" . $number_of_candidates . "', '" . $starting_date . "', '" . $ending_date . "', '" . $status . "','" . $inserted_by . "', '" . $inserted_on . "')")
        or die(mysqli_error($db));
    ?>
    <script>location.assign("index.php?addElectionPage=1&added=1")</script>
    <?php
}
?>
