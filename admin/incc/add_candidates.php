<?php
if (isset($_GET['added'])) { 
    ?>
    <div class="alert alert-success my-3" role="alert">
        Candidate has been added successfully!
    </div>
    <?php
  // Check if the uploaded image was too large
} else if (isset($_GET['largeFile'])) { 
    ?>
    <div class="alert alert-danger my-3" role="alert">
        Candidate image is too large, please upload a smaller file (You can upload up to 4MB)...
    </div>
    <?php
  // Check if the uploaded image was of an invalid type
} else if (isset($_GET['invalidFile'])) { 
    ?>
    <div class="alert alert-danger my-3" role="alert">
        Invalid image type (Only .jpg, .png, .jpeg files are allowed!)
    </div>
    <?php
} else if (isset($_GET['failed'])) {
    ?>
    <div class="alert alert-danger my-3" role="alert">
        Image uploading failed, please try again!
    </div>
    <?php
} else if (isset($_GET['delete_candidate_id'])) { 
    mysqli_query($db, "DELETE FROM candidate_details WHERE id ='" . $_GET['delete_candidate_id'] . "'") OR die(mysqli_error($db));
  
    ?>
    <div class="alert alert-danger my-3" role="alert">
        Candidate has been deleted successfully!
    </div>
    <?php
}
?>

<div class="row my-3">
    <div class="col-4">
        <h3 style="color: white;">Add New Candidates</h3>
      
         <!-- Form to add a new candidate -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <select class="form-control" name="election_id" required> 
                    <option value=""> Select Election </option>
                    <?php
                  // Fetch all elections from the 'elections' table.
                    $fetchingElections = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db)); 
                    $isAnyElectionAdded = mysqli_num_rows($fetchingElections);

                    if ($isAnyElectionAdded > 0) { 
                        while ($row = mysqli_fetch_assoc($fetchingElections)) {
                            $election_id = $row['id'];
                            $election_name = $row['election_topic'];
                            $allowed_candidates = $row['no_of_candidates'];

                            $fetchingCandidate = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '" . $election_id . "'") or die(mysqli_error($db));
                            $added_candidates = mysqli_num_rows($fetchingCandidate); 

                            if ($added_candidates < $allowed_candidates) { 
                                ?>
                                <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?></option> 
                                <?php
                            }
                        }
                    } else { 
                        ?>
                        <option value=""> Please Add Election First</option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control" required /> 
            </div>
            <div class="form-group">
                <input type="file" name="candidate_photo" class="form-control" required /> 
            </div>
            <div class="form-group">
                <input type="text" name="candidate_details" placeholder="Candidate Details" class="form-control" required /> 
            </div>
            <div class="form-group">
                <input type="text" name="voter_id" placeholder="Voter ID" class="form-control" required /> 
            </div>
            <input type="submit" value="Add Candidate" name="addCandidateBtn" class="btn btn-success" /> 
        </form>
    </div>

    <div class="col-8">
        <h3 style="color: white;">Candidate Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="color: white;">S.No</th>
                    <th scope="col" style="color: white;">Photo</th>
                    <th scope="col" style="color: white;">Name</th>
                    <th scope="col" style="color: white;">Details</th>
                    <th scope="col" style="color: white;">Voter ID</th>
                    <th scope="col" style="color: white;">Election</th>
                    <th scope="col" style="color: white;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($db, "SELECT * FROM candidate_details") or die(mysqli_error($db)); 
                $isAnyCandidateAdded = mysqli_num_rows($fetchingData); 

                if ($isAnyCandidateAdded > 0) { 
                    $sno = 1; 
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id = $row['election_id'];
                        $fetchingElectionName = mysqli_query($db, "SELECT * FROM elections WHERE id ='" . $election_id . "'") or die(mysqli_error($db)); // Fetch the election name associated with the candidate.
                        $exeFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);

                        if ($exeFetchingElectionNameQuery) {
                            $election_name = $exeFetchingElectionNameQuery['election_topic']; // If the election is found, assign its name.
                        } else {
                            $election_name = "Election not found"; // If the election is not found, set a default message.
                        }

                        $candidate_photo = $row['candidate_photo'];
                        ?>
                        <tr>
                            <td style="color: white;"> <?php echo $sno++; ?></td> 
                            <td style="color: white;"> <img src="<?php echo $candidate_photo; ?>" class="candidate_photo" /> </td> 
                            <td style="color: white;"> <?php echo $row['candidate_name']; ?></td> 
                            <td style="color: white;"> <?php echo $row['candidate_details']; ?></td> 
                            <td style="color: white;"> <?php echo $row['voter_id']; ?></td> 
                            <td style="color: white;"> <?php echo $election_name; ?></td> 
                            <td>
                                <a href="edit_candidate.php?candidate_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a> 
                                <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $row['id']; ?>)">Delete</button> 
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">No candidates added yet!</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (c_id) => {
        let c = confirm("Are you sure you want to delete it?"); 
        if (c == true) {
            location.assign("index.php?addCandidatePage=1&delete_candidate_id=" + c_id); 
        }
    }
</script>

<?php
 // Check if the form to add a candidate was submitted.
if (isset($_POST['addCandidateBtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']); 
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']); 
    $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']); 
    $voter_id = mysqli_real_escape_string($db, $_POST['voter_id']); 
    $inserted_by = $_SESSION['username']; 
    $inserted_on = date("Y-m-d"); 

    // Photo upload logic starts 
    $targetted_folder = "../assets/img/candidate_photos/";
    $candidate_photo = $targetted_folder . rand(1111111111, 9999999999) . "_" . rand(1111111111, 9999999999) . $_FILES['candidate_photo']['name']; 
    // Generate a unique name for the photo to avoid conflicts.
    $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name']; 
    $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION)); 
    $allowed_types = array("jpg", "jpeg", "png"); 
    $image_size = $_FILES['candidate_photo']['size']; 

    if ($image_size < 4000000) // Check if the image size is less than 4MB.
    {
        if (in_array($candidate_photo_type, $allowed_types)) { 
            if (move_uploaded_file($candidate_photo_tmp_name, $candidate_photo)) { 
                // Insert candidate details into the database
                mysqli_query($db, "INSERT INTO candidate_details(election_id, candidate_name, candidate_details, candidate_photo, voter_id, inserted_by, inserted_on) VALUES ('" . $election_id . "', '" . $candidate_name . "', '" . $candidate_details . "', '" . $candidate_photo . "', '" . $voter_id . "', '" . $inserted_by . "', '" . $inserted_on . "')")
                    or die(mysqli_error($db)); .

                echo "<script> location.assign('index.php?addCandidatePage=1&added=1'); </script>"; 
            } else {
                echo "<script> location.assign('index.php?addCandidatePage=1&failed=1'); </script>"; 
            }
        } else {
            echo "<script> location.assign('index.php?addCandidatePage=1&invalidFile=1'); </script>"; 
        }
    } else {
        echo "<script> location.assign('index.php?addCandidatePage=1&largeFile=1'); </script>"; 
    }
    // Photo upload logic ends
}
?>


