<?php
require_once("incc/config.php");

// Handle Election Edit
if (isset($_GET['election_id'])) {
    $id = mysqli_real_escape_string($db, $_GET['election_id']);
    $query = "SELECT * FROM elections WHERE id='$id'";
    $result = mysqli_query($db, $query);
    $election = mysqli_fetch_assoc($result);

     // Status update based on dates
     $starting_date = new DateTime($election['starting_date']);
     $ending_date = new DateTime($election['ending_date']);
     $current_date = new DateTime();
 
     if ($current_date < $starting_date) {
         $status = 'Inactive';
     } elseif ($current_date >= $starting_date && $current_date <= $ending_date) {
         $status = 'Active';
     } else {
         $status = 'Expired';
     }
 
     // Update the status in the database
     $update_status_query = "UPDATE elections SET status='$status' WHERE id='$id'";
     mysqli_query($db, $update_status_query);
}

if (isset($_POST['updateElectionBtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);

     // Status update based on dates
     $starting_date_obj = new DateTime($starting_date);
     $ending_date_obj = new DateTime($ending_date);
     $current_date = new DateTime();
 
     if ($current_date < $starting_date_obj) {
         $status = 'Inactive';
     } elseif ($current_date >= $starting_date_obj && $current_date <= $ending_date_obj) {
         $status = 'Active';
     } else {
         $status = 'Expired';
     }
     
    // Prepare the SQL query to update the election details
    $query = "UPDATE elections SET election_topic='$election_topic', no_of_candidates='$number_of_candidates', starting_date='$starting_date', ending_date='$ending_date' WHERE id='$election_id'";
    mysqli_query($db, $query) or die(mysqli_error($db)); // Execute the query and handle any errors

    // Redirect to the index page with a success indicator
    header("Location: index.php?addElectionPage=1&edit=success");
    exit(); // Stop further script execution
}
?>

<!-- Election Edit Form -->
<?php if (isset($_GET['election_id'])): ?>
<form method="POST">
    <input type="hidden" name="election_id" value="<?php echo htmlspecialchars($election['id']); ?>" />
    <div class="form-group">
        <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" value="<?php echo htmlspecialchars($election['election_topic']); ?>" required />
    </div>
    <div class="form-group">
        <input type="number" name="number_of_candidates" placeholder="Number Of Candidates" class="form-control" value="<?php echo htmlspecialchars($election['no_of_candidates']); ?>" required />
    </div>
    <div class="form-group">
        <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Starting Date" class="form-control" value="<?php echo htmlspecialchars($election['starting_date']); ?>" required />
    </div>
    <div class="form-group">
        <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date" class="form-control" value="<?php echo htmlspecialchars($election['ending_date']); ?>" required />
    </div>
    <input type="submit" value="Update Election" name="updateElectionBtn" class="btn btn-success" />
</form>
<?php endif; ?>
